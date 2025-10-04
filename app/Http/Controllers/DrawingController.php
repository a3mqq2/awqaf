<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use Illuminate\Http\Request;

class DrawingController extends Controller
{
    public function index(Request $request)
    {
        $query = Drawing::withCount('examinees');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'examinees_desc':
                    $query->orderBy('examinees_count', 'desc');
                    break;
                case 'examinees_asc':
                    $query->orderBy('examinees_count', 'asc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $perPage = $request->get('per_page', 10);
        $drawings = $query->paginate($perPage)->appends($request->query());

        return view('drawings.index', compact('drawings'));
    }

    public function create()
    {
        return view('drawings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:drawings,name',
        ]);

        Drawing::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('drawings.index')->with('success', 'تمت إضافة الرسم بنجاح');
    }

    public function edit(Drawing $drawing)
    {
        return view('drawings.edit', compact('drawing'));
    }

    public function update(Request $request, Drawing $drawing)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:drawings,name,' . $drawing->id,
        ]);

        $drawing->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('drawings.index')->with('success', 'تم تعديل الرسم بنجاح');
    }

    public function destroy(Drawing $drawing)
    {
        $drawing->delete();

        return redirect()->route('drawings.index')->with('success', 'تم حذف الرسم بنجاح');
    }

    public function toggle(Drawing $drawing)
    {
        $drawing->is_active = ! $drawing->is_active;
        $drawing->save();

        return redirect()->route('drawings.index')->with('success', 'تم تغيير حالة الرسم بنجاح');
    }
}
