<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\OralEvaluation;
use App\Models\Committee;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OralEvaluationController extends Controller
{
    /**
     * لوحة الاختبار الشفهي
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('judge')) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        $committees = $user->committees;

        if ($committees->isEmpty()) {
            return view('judge.oral.dashboard', [
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

        $evaluatedExamineeIds = OralEvaluation::where('judge_id', $user->id)
            ->where('status', 'completed')
            ->pluck('examinee_id')
            ->toArray();

        $userClusterIds = $user->clusters->pluck('id')->toArray();
        $examinees = Examinee::where('status', 'attended')
            ->whereHas('evaluations', function($q) {
                $q->where('status', 'completed')
                  ->where('score', '>=', 28);
            })
            ->when(!empty($evaluatedExamineeIds), function($query) use ($evaluatedExamineeIds) {
                return $query->whereNotIn('id', $evaluatedExamineeIds);
            })
            ->with(['narration', 'cluster'])
            ->when(!empty($userClusterIds), function($query) use ($userClusterIds) {
                return $query->whereIn('cluster_id', $userClusterIds);
            })            ->orderBy('attended_at', 'asc')
            ->get();

        $myEvaluations = OralEvaluation::where('judge_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['examinee'])
            ->get()
            ->keyBy('examinee_id');

        $statistics = [
            'waiting' => OralEvaluation::where('judge_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'in_progress' => OralEvaluation::where('judge_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'completed' => OralEvaluation::where('judge_id', $user->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('judge.oral.dashboard', compact('committees', 'examinees', 'myEvaluations', 'statistics'));
    }

    /**
     * استقبال ممتحن للاختبار الشفهي
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

        if (!$user->committees->pluck('id')->contains($committee->id)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالعمل في هذه اللجنة'
            ], 403);
        }

        if (!$examinee->hasPassedWrittenTest()) {
            return response()->json([
                'success' => false,
                'message' => 'الممتحن لم يجتز اختبار المنهج العلمي بعد'
            ]);
        }

        $existingEvaluation = OralEvaluation::where('examinee_id', $examinee->id)
            ->where('judge_id', $user->id)
            ->first();

        if ($existingEvaluation) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا الممتحن شفهياً مسبقاً'
            ]);
        }

        $evaluation = OralEvaluation::create([
            'examinee_id' => $examinee->id,
            'judge_id' => $user->id,
            'committee_id' => $committee->id,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        // تهيئة الأسئلة
        $evaluation->initializeQuestions();

        SystemLog::create([
            'description' => "المحكم {$user->name} استقبل الممتحن: {$examinee->full_name} للاختبار الشفهي",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم استقبال الممتحن للاختبار الشفهي',
            'evaluation_id' => $evaluation->id
        ]);
    }

    /**
     * صفحة التقييم الشفهي
     */
    public function evaluate($evaluationId)
    {
        $evaluation = OralEvaluation::with(['examinee.narration.pdfs', 'committee.cluster'])->findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            abort(403, 'غير مصرح لك بهذا التقييم');
        }

        if ($evaluation->status == 'pending') {
            $evaluation->status = 'in_progress';
            $evaluation->save();
        }

        // التأكد من تهيئة الأسئلة
        if (empty($evaluation->questions_data)) {
            $evaluation->initializeQuestions();
        }

        return view('judge.oral.evaluate', compact('evaluation'));
    }

    /**
     * تحديث بند في السؤال الحالي
     */
    public function updateDeduction(Request $request, $evaluationId)
    {
        $request->validate([
            'type' => 'required|string',
            'action' => 'required|in:increment,decrement',
        ]);

        $evaluation = OralEvaluation::findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        $currentQ = $evaluation->current_question;
        $questions = $evaluation->questions_data;

        if (!isset($questions[$currentQ])) {
            return response()->json([
                'success' => false,
                'message' => 'السؤال غير موجود'
            ]);
        }

        $type = $request->type;
        
        if ($request->action == 'increment') {
            $questions[$currentQ]['deductions'][$type]++;
        } elseif ($request->action == 'decrement' && $questions[$currentQ]['deductions'][$type] > 0) {
            $questions[$currentQ]['deductions'][$type]--;
        }

        $evaluation->questions_data = $questions;
        $evaluation->save();

        $questionScore = $evaluation->calculateQuestionScore($currentQ);

        return response()->json([
            'success' => true,
            'question_score' => round($questionScore, 2),
            'deductions' => $questions[$currentQ]['deductions']
        ]);
    }

    /**
     * اعتماد السؤال الحالي
     */
    public function approveQuestion(Request $request, $evaluationId)
    {
        $evaluation = OralEvaluation::findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        $result = $evaluation->approveCurrentQuestion();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في اعتماد السؤال'
            ]);
        }

    // Check if we just approved question 12 (the last one)
    if ($evaluation->current_question == 12) {
        // Count approved questions
        $approvedCount = collect($evaluation->questions_data)
            ->where('is_approved', true)
            ->count();

        if ($approvedCount == 12) {
            $evaluation->status = 'completed';
            $evaluation->completed_at = now();
            $evaluation->calculateTotalScore();
            $evaluation->save();

            SystemLog::create([
                'description' => "المحكم {$user->name} أكمل تقييم الممتحن: {$evaluation->examinee->full_name} شفهياً بدرجة {$evaluation->final_score} (تلقائياً بعد السؤال 12)",
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم اعتماد السؤال الأخير وإكمال التقييم بنجاح',
                'current_question' => $evaluation->current_question,
                'total_score' => $evaluation->total_score,
                'final_score' => $evaluation->final_score,
                'is_completed' => true,
                'is_last_question' => true
            ]);
        }
    }


        return response()->json([
            'success' => true,
            'message' => 'تم اعتماد السؤال بنجاح',
            'current_question' => $evaluation->current_question,
            'total_score' => $evaluation->total_score,
            'is_last_question' => $evaluation->current_question > 12
        ]);
    }
    /**
     * التراجع عن السؤال السابق
     */
    public function previousQuestion(Request $request, $evaluationId)
    {
        $evaluation = OralEvaluation::findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        $result = $evaluation->goToPreviousQuestion();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن الرجوع للسؤال السابق'
            ]);
        }

        $currentQ = $evaluation->current_question;
        $questionData = $evaluation->questions_data[$currentQ];

        return response()->json([
            'success' => true,
            'message' => 'تم الرجوع للسؤال السابق',
            'current_question' => $currentQ,
            'question_data' => $questionData,
            'total_score' => $evaluation->total_score
        ]);
    }

    /**
     * حفظ التقييم النهائي
     */
    public function save(Request $request, $evaluationId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $evaluation = OralEvaluation::with('examinee')->findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        if ($evaluation->status == 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'تم حفظ هذا التقييم مسبقاً'
            ]);
        }

        // التحقق من اعتماد جميع الأسئلة
        $approvedCount = 0;
        foreach ($evaluation->questions_data as $question) {
            if ($question['is_approved']) {
                $approvedCount++;
            }
        }

        if ($approvedCount < 12) {
            return response()->json([
                'success' => false,
                'message' => "يجب اعتماد جميع الأسئلة الـ 12 قبل حفظ التقييم. تم اعتماد {$approvedCount} سؤال فقط."
            ]);
        }

        $evaluation->notes = $request->notes;
        $evaluation->status = 'completed';
        $evaluation->completed_at = now();
        $evaluation->calculateTotalScore();
        $evaluation->save();

        SystemLog::create([
            'description' => "المحكم {$user->name} قيّم الممتحن: {$evaluation->examinee->full_name} شفهياً بدرجة {$evaluation->final_score}",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التقييم بنجاح',
            'final_score' => $evaluation->final_score
        ]);
    }

    /**
     * استبعاد ممتحن
     */
    public function exclude($evaluationId)
    {
        $evaluation = OralEvaluation::with('examinee')->findOrFail($evaluationId);
        $user = Auth::user();

        if ($evaluation->judge_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بهذا التقييم'
            ], 403);
        }

        $evaluation->status = 'excluded';
        $evaluation->final_score = 0;
        $evaluation->total_score = 0;
        $evaluation->completed_at = now();
        $evaluation->save();

        SystemLog::create([
            'description' => "المحكم {$user->name} استبعد الممتحن: {$evaluation->examinee->full_name} من الاختبار الشفهي",
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم استبعاد الممتحن'
        ]);
    }

    /**
     * التقييمات المكتملة
     */
    public function completed()
    {
        $user = Auth::user();

        $evaluations = OralEvaluation::where('judge_id', $user->id)
            ->whereIn('status', ['completed', 'excluded'])
            ->with(['examinee.narration', 'committee'])
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        return view('judge.oral.completed', compact('evaluations'));
    }
}