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
use App\Models\OralEvaluation;
use App\Models\ExamineeEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // إذا كان المستخدم محكم، عرض لوحة المحكم
        if ($user->hasRole('judge')) {
            return $this->judgeDashboard();
        }
        
        // إذا كان المستخدم مكتب، عرض لوحة المكتب
        if ($user->hasRole('office')) {
            return $this->officeDashboard();
        }
        
        // الأدوار الأخرى (Admin, Manager, etc.)
        return $this->adminDashboard();
    }

    /**
     * لوحة تحكم المحكم
     */
    protected function judgeDashboard()
    {
        $user = Auth::user();
        
        // إحصائيات التقييم المكتوب (المنهج العلمي)
        $writtenEvaluations = ExamineeEvaluation::where('judge_id', $user->id)->count();
        $writtenCompleted = ExamineeEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $writtenInProgress = ExamineeEvaluation::where('judge_id', $user->id)
            ->where('status', 'in_progress')
            ->count();
        $writtenPending = ExamineeEvaluation::where('judge_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // إحصائيات التقييم الشفهي
        $oralEvaluations = OralEvaluation::where('judge_id', $user->id)->count();
        $oralCompleted = OralEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $oralInProgress = OralEvaluation::where('judge_id', $user->id)
            ->where('status', 'in_progress')
            ->count();
        $oralPending = OralEvaluation::where('judge_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        // متوسط الدرجات
        $averageWrittenScore = ExamineeEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->avg('score') ?? 0;
        
        $averageOralScore = OralEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->avg('final_score') ?? 0;
        
        // الممتحنين المتاحين للتقييم المكتوب
        $evaluatedExamineeIds = ExamineeEvaluation::where('judge_id', $user->id)
            ->pluck('examinee_id')
            ->toArray();
        
        $availableForWritten = Examinee::where('status', 'attended')
            ->when(!empty($evaluatedExamineeIds), function($query) use ($evaluatedExamineeIds) {
                return $query->whereNotIn('id', $evaluatedExamineeIds);
            })
            ->count();
        
        // الممتحنين المتاحين للتقييم الشفهي
        $oralEvaluatedIds = OralEvaluation::where('judge_id', $user->id)
            ->pluck('examinee_id')
            ->toArray();
        
        $availableForOral = Examinee::where('status', 'attended')
            ->whereHas('evaluations', function($q) {
                $q->where('status', 'completed')
                  ->where('score', '>=', 28);
            })
            ->when(!empty($oralEvaluatedIds), function($query) use ($oralEvaluatedIds) {
                return $query->whereNotIn('id', $oralEvaluatedIds);
            })
            ->count();
        
        // آخر التقييمات
        $latestWrittenEvaluations = ExamineeEvaluation::where('judge_id', $user->id)
            ->with(['examinee', 'committee'])
            ->latest()
            ->limit(5)
            ->get();
        
        $latestOralEvaluations = OralEvaluation::where('judge_id', $user->id)
            ->with(['examinee', 'committee'])
            ->latest()
            ->limit(5)
            ->get();
        
        // البيانات للرسوم البيانية
        $statusData = [
            'completed' => $writtenCompleted + $oralCompleted,
            'in_progress' => $writtenInProgress + $oralInProgress,
            'pending' => $writtenPending + $oralPending,
        ];
        
        $evaluationTypeData = [
            'written' => $writtenEvaluations,
            'oral' => $oralEvaluations,
        ];
        
        return view('dashboard.judge', compact(
            'writtenEvaluations',
            'writtenCompleted',
            'writtenInProgress',
            'writtenPending',
            'oralEvaluations',
            'oralCompleted',
            'oralInProgress',
            'oralPending',
            'averageWrittenScore',
            'averageOralScore',
            'availableForWritten',
            'availableForOral',
            'latestWrittenEvaluations',
            'latestOralEvaluations',
            'statusData',
            'evaluationTypeData'
        ));
    }

    /**
     * لوحة تحكم المكتب
     */
    protected function officeDashboard()
    {
        $user = Auth::user();
        
        // الممتحنين التابعين للمكتب
        $officeExaminees = Examinee::where('office_id', $user->office_id);
        
        $totalExaminees = (clone $officeExaminees)->count();
        $confirmedExaminees = (clone $officeExaminees)->where('status', 'confirmed')->count();
        $pendingExaminees = (clone $officeExaminees)->where('status', 'pending')->count();
        $withdrawnExaminees = (clone $officeExaminees)->where('status', 'withdrawn')->count();
        
        $maleExaminees = (clone $officeExaminees)->where('gender', 'male')->count();
        $femaleExaminees = (clone $officeExaminees)->where('gender', 'female')->count();
        
        $recentExaminees = (clone $officeExaminees)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        
        // آخر الممتحنين
        $latestExaminees = (clone $officeExaminees)
            ->with(['cluster', 'narration'])
            ->latest()
            ->limit(5)
            ->get();
        
        // التوزيع حسب التجمع
        $examineesByCluster = (clone $officeExaminees)
            ->select('cluster_id', DB::raw('count(*) as total'))
            ->with('cluster')
            ->whereNotNull('cluster_id')
            ->groupBy('cluster_id')
            ->orderByDesc('total')
            ->get();
        
        // التوزيع حسب الرواية
        $examineesByNarration = (clone $officeExaminees)
            ->select('narration_id', DB::raw('count(*) as total'))
            ->with('narration')
            ->whereNotNull('narration_id')
            ->groupBy('narration_id')
            ->orderByDesc('total')
            ->get();
        
        $statusData = [
            'confirmed' => $confirmedExaminees,
            'pending' => $pendingExaminees,
            'withdrawn' => $withdrawnExaminees,
        ];
        
        $genderData = [
            'male' => $maleExaminees,
            'female' => $femaleExaminees,
        ];
        
        return view('dashboard.office', compact(
            'totalExaminees',
            'confirmedExaminees',
            'pendingExaminees',
            'withdrawnExaminees',
            'maleExaminees',
            'femaleExaminees',
            'recentExaminees',
            'latestExaminees',
            'examineesByCluster',
            'examineesByNarration',
            'statusData',
            'genderData'
        ));
    }

    /**
     * لوحة تحكم الأدمن
     */
    protected function adminDashboard()
    {
        $user = Auth::user();
        
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
            'statusData',
            'genderData',
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