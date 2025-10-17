<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\Committee;
use App\Models\ExamineeEvaluation;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JudgeDashboardController extends Controller
{
    /**
     * لوحة المحكم - قائمة الانتظار
     */
    public function index()
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$user->hasRole('judge')) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        // جلب اللجان المخصصة للمحكم
        $committees = $user->committees;

        if ($committees->isEmpty()) {
            return view('judge.dashboard', [
                'committees' => collect(),
                'examinees' => collect(),
                'myEvaluations' => collect(),
                'statistics' => [
                    'waiting' => 0,
                    'in_progress' => 0,
                    'completed' => 0,
                ]
            ]);
        }

        // جلب الممتحنين الحاضرين الذين لم يقيمهم المحكم
        $examinees = Examinee::where('status', 'attended')
            ->whereDoesntHave('evaluations', function($q) use ($user) {
                $q->where('judge_id', $user->id)->where('status',  'completed');
            })
            ->with(['narration', 'cluster'])
            ->where('cluster_id', $user->clusters->pluck('id'))
            ->orderBy('attended_at', 'asc')
            ->get();

        // جلب التقييمات الحالية للمحكم
        $myEvaluations = ExamineeEvaluation::where('judge_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['examinee'])
            ->get()
            ->keyBy('examinee_id');

        // إحصائيات
        $statistics = [
            'waiting' => ExamineeEvaluation::where('judge_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'in_progress' => ExamineeEvaluation::where('judge_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'completed' => ExamineeEvaluation::where('judge_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('judge.dashboard', compact('committees', 'examinees', 'myEvaluations', 'statistics'));
    }

    /**
     * استقبال ممتحن
     */
    public function receiveExaminee(Request $request)
    {
        $request->validate([
            'examinee_id' => 'required|exists:examinees,id',
            'committee_id' => 'required|exists:committees,id',
        ]);

        $user = Auth::user();
        $examinee = Examinee::findOrFail($request->examinee_id);
        $committee = Committee::findOrFail($request->committee_id);

        // التحقق من أن المحكم في اللجنة
        if (!$user->committees->pluck('id')->contains($committee->id)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالعمل في هذه اللجنة'
            ], 403);
        }

        // التحقق من أن الممتحن حضر
        if ($examinee->status != 'attended') {
            return response()->json([
                'success' => false,
                'message' => 'الممتحن لم يسجل حضوره بعد'
            ]);
        }

        // التحقق من عدم استقبال الممتحن مسبقاً من نفس المحكم
        $existingEvaluation = ExamineeEvaluation::where('examinee_id', $examinee->id)
            ->where('judge_id', $user->id)
            ->first();

        if ($existingEvaluation) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا الممتحن مسبقاً'
            ]);
        }

        // إنشاء تقييم جديد
        $evaluation = ExamineeEvaluation::create([
            'examinee_id' => $examinee->id,
            'judge_id' => $user->id,
            'committee_id' => $committee->id,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        // Log
        SystemLog::create([
            'description' => "المحكم {$user->name} استقبل الممتحن: {$examinee->full_name}",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم استقبال الممتحن بنجاح',
            'evaluation_id' => $evaluation->id
        ]);
    }

    /**
     * بدء التقييم
     */
    public function startEvaluation(Request $request, $evaluationId)
    {
        $evaluation = ExamineeEvaluation::with(['examinee.narration.pdfs', 'committee.cluster'])->findOrFail($evaluationId);
        $user = Auth::user();

      

        // تحديث حالة التقييم
        if ($evaluation->status == 'pending') {
            $evaluation->status = 'in_progress';
            $evaluation->save();
        }

        return view('judge.evaluate', compact('evaluation'));
    }

    /**
     * حفظ التقييم
     */
    public function saveEvaluation(Request $request, $evaluationId)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:40',
            'notes' => 'nullable|string|max:1000',
        ]);

        $evaluation = ExamineeEvaluation::with('examinee')->findOrFail($evaluationId);
        $user = Auth::user();

        // التحقق من أن التقييم للمحكم الحالي
        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        // التحقق من عدم حفظ التقييم مسبقاً
        if ($evaluation->status == 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'تم حفظ هذا التقييم مسبقاً'
            ]);
        }

        // حفظ التقييم
        $evaluation->score = $request->score;
        $evaluation->notes = $request->notes;
        $evaluation->status = 'completed';
        $evaluation->completed_at = now();
        $evaluation->save();

        // Log
        SystemLog::create([
            'description' => "المحكم {$user->name} قيّم الممتحن: {$evaluation->examinee->full_name} بدرجة {$request->score}",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التقييم بنجاح'
        ]);
    }

    /**
     * التقييمات المكتملة
     */
    public function completedEvaluations()
    {
        $user = Auth::user();

        $evaluations = ExamineeEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->with(['examinee.narration', 'committee'])
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('judge.completed', compact('evaluations'));
    }
}