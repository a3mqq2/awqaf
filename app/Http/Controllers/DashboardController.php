<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Drawing;
use App\Models\Examinee;
use App\Models\Narration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's cluster IDs
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // Build base query with cluster filtering
        $examineeQuery = Examinee::query();
        
        // Apply cluster filter if user has specific clusters
        if (!empty($userClusterIds)) {
            $examineeQuery->whereIn('cluster_id', $userClusterIds);
        }
        
        // Total counts
        $totalExaminees = (clone $examineeQuery)->count();
        $totalOffices = Office::where('is_active', true)->count();
        $totalClusters = !empty($userClusterIds) 
            ? count($userClusterIds) 
            : Cluster::where('is_active', true)->count();
        $totalUsers = User::where('is_active', true)->count();
        
        // Examinees by status
        $confirmedExaminees = (clone $examineeQuery)->where('status', 'confirmed')->count();
        $pendingExaminees = (clone $examineeQuery)->where('status', 'pending')->count();
        $withdrawnExaminees = (clone $examineeQuery)->where('status', 'withdrawn')->count();
        
        // Examinees by gender
        $maleExaminees = (clone $examineeQuery)->where('gender', 'male')->count();
        $femaleExaminees = (clone $examineeQuery)->where('gender', 'female')->count();
        
        // Recent examinees (last 7 days)
        $recentExaminees = (clone $examineeQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        
        // Examinees by office (top 5)
        $examineesByOffice = (clone $examineeQuery)
            ->select('office_id', DB::raw('count(*) as total'))
            ->with('office')
            ->whereNotNull('office_id')
            ->groupBy('office_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Examinees by cluster (top 5)
        $examineesByCluster = (clone $examineeQuery)
            ->select('cluster_id', DB::raw('count(*) as total'))
            ->with('cluster')
            ->whereNotNull('cluster_id')
            ->groupBy('cluster_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Examinees by narration (top 5)
        $examineesByNarration = (clone $examineeQuery)
            ->select('narration_id', DB::raw('count(*) as total'))
            ->with('narration')
            ->whereNotNull('narration_id')
            ->groupBy('narration_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Examinees by drawing (top 5)
        $examineesByDrawing = (clone $examineeQuery)
            ->select('drawing_id', DB::raw('count(*) as total'))
            ->with('drawing')
            ->whereNotNull('drawing_id')
            ->groupBy('drawing_id')
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
        
        // Narration distribution for chart
        $narrationData = (clone $examineeQuery)
            ->select('narration_id', DB::raw('count(*) as total'))
            ->with('narration')
            ->whereNotNull('narration_id')
            ->groupBy('narration_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();
        
        // Drawing distribution for chart
        $drawingData = (clone $examineeQuery)
            ->select('drawing_id', DB::raw('count(*) as total'))
            ->with('drawing')
            ->whereNotNull('drawing_id')
            ->groupBy('drawing_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();
        
        // Latest examinees
        $latestExaminees = (clone $examineeQuery)
            ->with(['office', 'cluster'])
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
            'examineesByNarration',
            'examineesByDrawing',
            'statusData',
            'genderData',
            'narrationData',
            'drawingData',
            'latestExaminees'
        ));
    }


    public function send(Request $request)
    {
        // التحقق من البيانات
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'email_to' => 'required|email',
            'national_id' => 'nullable|string',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'city.required' => 'المدينة مطلوبة',
            'message.required' => 'الرسالة مطلوبة',
            'email_to.required' => 'البريد الإلكتروني مطلوب',
            'email_to.email' => 'البريد الإلكتروني غير صحيح',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        try {
            // إرسال البريد الإلكتروني
            Mail::send('emails.contact', [
                'contactName' => $request->name,
                'contactPhone' => $request->phone,
                'contactCity' => $request->city,
                'contactMessage' => $request->message,
                'contactNationalId' => $request->national_id,
            ], function ($message) use ($request) {
                $message->to($request->email_to)
                        ->subject('رسالة جديدة من نظام التسجيل - ' . $request->name);
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            return back()->with('success', 'تم إرسال رسالتك بنجاح. سيتم التواصل معك قريباً.');
            
        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.');
        }
    }
}