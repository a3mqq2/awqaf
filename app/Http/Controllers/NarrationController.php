<?php

namespace App\Http\Controllers;

use App\Models\Narration;
use Illuminate\Http\Request;

class NarrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Narration::query();

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
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $narrations = $query->paginate(10);

        return view('narrations.index', compact('narrations'));
    }

    public function create()
    {
        return view('narrations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:narrations,name',
        ]);

        Narration::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('narrations.index')->with('success', 'تمت إضافة الرواية بنجاح');
    }

    public function edit(Narration $narration)
    {
        return view('narrations.edit', compact('narration'));
    }

    public function update(Request $request, Narration $narration)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:narrations,name,' . $narration->id,
        ]);

        $narration->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('narrations.index')->with('success', 'تم تعديل الرواية بنجاح');
    }

    public function destroy(Narration $narration)
    {
        $narration->delete();

        return redirect()->route('narrations.index')->with('success', 'تم حذف الرواية بنجاح');
    }

    public function toggle(Narration $narration)
    {
        $narration->is_active = ! $narration->is_active;
        $narration->save();

        return redirect()->route('narrations.index')->with('success', 'تم تغيير حالة الرواية بنجاح');
    }
}
