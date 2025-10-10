<?php

namespace App\Http\Controllers;

use App\Models\Narration;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NarrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Narration::withCount('examinees');
    
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
                case 'examinees_asc':
                    $query->orderBy('examinees_count', 'asc');
                    break;
                case 'examinees_desc':
                    $query->orderBy('examinees_count', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
    
        $perPage = $request->get('per_page', 10);
        $narrations = $query->paginate($perPage)->appends($request->query());
    
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

        $narration = Narration::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        // Log
        SystemLog::create([
            'description' => "تمت إضافة الرواية: {$narration->name}",
            'user_id'     => Auth::id(),
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

        // Log
        SystemLog::create([
            'description' => "تم تعديل الرواية: {$narration->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('narrations.index')->with('success', 'تم تعديل الرواية بنجاح');
    }

    public function destroy(Narration $narration)
    {
        $name = $narration->name;
        $narration->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف الرواية: {$name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('narrations.index')->with('success', 'تم حذف الرواية بنجاح');
    }

    public function toggle(Narration $narration)
    {
        $narration->is_active = ! $narration->is_active;
        $narration->save();

        $status = $narration->is_active ? 'تفعيل' : 'إلغاء التفعيل';

        // Log
        SystemLog::create([
            'description' => "تم {$status} الرواية: {$narration->name}",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('narrations.index')->with('success', 'تم تغيير حالة الرواية بنجاح');
    }
}
