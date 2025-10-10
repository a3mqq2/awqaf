<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $query = Office::withCount('examinees');

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
        $offices = $query->paginate($perPage)->appends($request->query());

        return view('offices.index', compact('offices'));
    }

    public function create()
    {
        return view('offices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name',
        ]);

        $office = Office::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Log
        SystemLog::create([
            'description' => "تمت إضافة المكتب: {$office->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('offices.index')->with('success', 'تمت إضافة المكتب بنجاح');
    }

    public function edit(Office $office)
    {
        return view('offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:offices,name,' . $office->id,
        ]);

        $office->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Log
        SystemLog::create([
            'description' => "تم تعديل المكتب: {$office->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('offices.index')->with('success', 'تم تحديث المكتب بنجاح');
    }

    public function destroy(Office $office)
    {
        $name = $office->name;
        $office->delete();

        SystemLog::create([
            'description' => "تم حذف المكتب: {$name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('offices.index')->with('success', 'تم حذف المكتب بنجاح');
    }

    public function toggle(Office $office)
    {
        $office->update(['is_active' => !$office->is_active]);

        $status = $office->is_active ? 'تفعيل' : 'إلغاء التفعيل';

        SystemLog::create([
            'description' => "تم {$status} المكتب: {$office->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('offices.index')->with('success', 'تم تغيير حالة المكتب بنجاح');
    }
}
