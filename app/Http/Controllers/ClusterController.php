<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;

class ClusterController extends Controller
{
    /**
     * Display a listing of clusters.
     */
    public function index()
    {
        $clusters = Cluster::latest()->paginate(10);

        return view('clusters.index', compact('clusters'));
    }

    /**
     * Show the form for creating a new cluster.
     */
    public function create()
    {
        return view('clusters.create');
    }

    /**
     * Store a newly created cluster in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clusters,name',
        ]);

        Cluster::create([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true), // افتراضي true
        ]);

        return redirect()->route('clusters.index')->with('success', 'تمت إضافة التجمع بنجاح');
    }

    /**
     * Show the form for editing the specified cluster.
     */
    public function edit(Cluster $cluster)
    {
        return view('clusters.edit', compact('cluster'));
    }

    /**
     * Update the specified cluster in storage.
     */
    public function update(Request $request, Cluster $cluster)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clusters,name,' . $cluster->id,
        ]);

        $cluster->update([
            'name' => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('clusters.index')->with('success', 'تم تحديث التجمع بنجاح');
    }

    /**
     * Remove the specified cluster from storage.
     */
    public function destroy(Cluster $cluster)
    {
        $cluster->delete();

        return redirect()->route('clusters.index')->with('success', 'تم حذف التجمع بنجاح');
    }

    /**
     * Toggle activation status of a cluster.
     */
    public function toggle(Cluster $cluster)
    {
        $cluster->update(['is_active' => !$cluster->is_active]);

        return redirect()->route('clusters.index')->with('success', 'تم تغيير حالة التفعيل');
    }
}