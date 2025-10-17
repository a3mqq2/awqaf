<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClusterController extends Controller
{
    public function index(Request $request)
    {
        $query = Cluster::query();
    
        $query->withCount(['examinees' => function ($q) use ($request) {
            if ($request->filled('examinee_status')) {
                $q->where('status', $request->examinee_status);
            }
        }]);
    
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
        $clusters = $query->paginate($perPage)->appends($request->query());
    
        return view('clusters.index', compact('clusters'));
    }
    

    public function create()
    {
        return view('clusters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clusters,name',
        ]);

        $cluster = Cluster::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // سجل العملية
        SystemLog::create([
            'description' => "قام المستخدم بإضافة تجمع جديد: {$cluster->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clusters.index')->with('success', 'تمت إضافة التجمع بنجاح');
    }

    public function edit(Cluster $cluster)
    {
        return view('clusters.edit', compact('cluster'));
    }

    public function update(Request $request, Cluster $cluster)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clusters,name,' . $cluster->id,
        ]);

        $cluster->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // سجل العملية
        SystemLog::create([
            'description' => "قام المستخدم بتحديث بيانات التجمع: {$cluster->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clusters.index')->with('success', 'تم تحديث التجمع بنجاح');
    }

    public function destroy(Cluster $cluster)
    {
        $name = $cluster->name;
        $cluster->delete();

        // سجل العملية
        SystemLog::create([
            'description' => "قام المستخدم بحذف التجمع: {$name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clusters.index')->with('success', 'تم حذف التجمع بنجاح');
    }

    public function toggle(Cluster $cluster)
    {
        $cluster->update(['is_active' => !$cluster->is_active]);

        // سجل العملية
        $status = $cluster->is_active ? 'تفعيل' : 'إلغاء التفعيل';
        SystemLog::create([
            'description' => "قام المستخدم بعملية {$status} للتجمع: {$cluster->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('clusters.index')->with('success', 'تم تغيير حالة التفعيل');
    }
}
