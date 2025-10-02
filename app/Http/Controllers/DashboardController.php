<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalExaminees = Examinee::count();
        $totalOffices = Office::where('is_active', true)->count();
        $totalClusters = Cluster::where('is_active', true)->count();
        $totalUsers = User::where('is_active', true)->count();
        
        // Examinees by status
        $confirmedExaminees = Examinee::where('status', 'confirmed')->count();
        $pendingExaminees = Examinee::where('status', 'pending')->count();
        $withdrawnExaminees = Examinee::where('status', 'withdrawn')->count();
        
        // Examinees by gender
        $maleExaminees = Examinee::where('gender', 'male')->count();
        $femaleExaminees = Examinee::where('gender', 'female')->count();
        
        // Recent examinees (last 7 days)
        $recentExaminees = Examinee::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        // Examinees by office (top 5)
        $examineesByOffice = Examinee::select('office_id', DB::raw('count(*) as total'))
            ->with('office')
            ->groupBy('office_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Examinees by cluster (top 5)
        $examineesByCluster = Examinee::select('cluster_id', DB::raw('count(*) as total'))
            ->with('cluster')
            ->groupBy('cluster_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Status distribution for chart
        $statusData = [
            'confirmed' => $confirmedExaminees,
            'pending' => $pendingExaminees,
            'withdrawn' => $withdrawnExaminees,
        ];
        
        // Gender distribution for chart
        $genderData = [
            'male' => $maleExaminees,
            'female' => $femaleExaminees,
        ];
        
        // Latest examinees
        $latestExaminees = Examinee::with(['office', 'cluster'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalExaminees',
            'totalOffices',
            'totalClusters',
            'totalUsers',
            'confirmedExaminees',
            'pendingExaminees',
            'withdrawnExaminees',
            'maleExaminees',
            'femaleExaminees',
            'recentExaminees',
            'examineesByOffice',
            'examineesByCluster',
            'statusData',
            'genderData',
            'latestExaminees'
        ));
    }
}