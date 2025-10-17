<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrawingController extends Controller
{
    public function index(Request $request)
    {
        $query = Drawing::withCount('examinees');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
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

        $drawing = Drawing::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        SystemLog::create([
            'description' => "قام المستخدم بإضافة رسم جديد: {$drawing->name}",
            'user_id'     => Auth::id(),
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

        SystemLog::create([
            'description' => "قام المستخدم بتعديل بيانات الرسم: {$drawing->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('drawings.index')->with('success', 'تم تعديل الرسم بنجاح');
    }

    public function destroy(Drawing $drawing)
    {
        $name = $drawing->name;
        $drawing->delete();

        SystemLog::create([
            'description' => "قام المستخدم بحذف الرسم: {$name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('drawings.index')->with('success', 'تم حذف الرسم بنجاح');
    }

    public function toggle(Drawing $drawing)
    {
        $drawing->is_active = ! $drawing->is_active;
        $drawing->save();

        $status = $drawing->is_active ? 'تفعيل' : 'إلغاء التفعيل';
        SystemLog::create([
            'description' => "قام المستخدم بعملية {$status} للرسم: {$drawing->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('drawings.index')->with('success', 'تم تغيير حالة الرسم بنجاح');
    }
}
