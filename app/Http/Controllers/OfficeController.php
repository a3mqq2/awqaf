<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(Request $request)
    {
        $query = Office::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'name') {
                $query->orderBy('name');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $offices = $query->paginate(10);

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
            'is_active' => 'nullable|boolean',
        ]);

        Office::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
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
            'is_active' => 'nullable|boolean',
        ]);

        $office->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? $request->is_active : true,
        ]);

        return redirect()->route('offices.index')->with('success', 'تم تحديث المكتب بنجاح');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'تم حذف المكتب بنجاح');
    }

    public function toggle(Office $office)
    {
        $office->is_active = ! $office->is_active;
        $office->save();
        return redirect()->route('offices.index')->with('success', 'تم تغيير حالة المكتب بنجاح');
    }
}
