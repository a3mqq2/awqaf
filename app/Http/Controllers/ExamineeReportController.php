<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamineeReportController extends Controller
{
    /**
     * تقرير الممتحنين (للمدير)
     */
    public function index(Request $request)
    {

        $user = Auth::user();

        // إذا كان مدير لجنة، يعرض ممتحني لجنته فقط
        $query = Examinee::with(['narration', 'cluster', 'evaluations', 'oralEvaluations']);

        // فلترة حسب اللجنة إذا كان مدير لجنة
        if ($user->hasRole('manager') && $user->committee_id) {
            $committee = Committee::find($user->committee_id);
            if ($committee) {
                $clusterIds = [$committee->cluster_id];
                $query->whereIn('cluster_id', $clusterIds);
            }
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التجمع
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }


        $query->whereIn('cluster_id', $user->clusters->pluck('id'));

        // فلترة حسب النتيجة
        if ($request->filled('result')) {
            if ($request->result === 'passed') {
                $query->whereHas('evaluations', function($q) {
                    $q->where('status', 'completed');
                })->whereHas('oralEvaluations', function($q) {
                    $q->where('status', 'completed');
                });
            } elseif ($request->result === 'failed') {
                $query->whereDoesntHave('oralEvaluations', function($q) {
                    $q->where('status', 'completed');
                });
            }
        }

        // for user clusters just
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            $query->whereIn('cluster_id', $user->clusters->pluck('id'));
        }

        $examinees = $query->orderBy('full_name')->paginate(50);

        // حساب المتوسطات لكل ممتحن
        foreach ($examinees as $examinee) {
            $examinee->avg_written = $examinee->evaluations()
                ->where('status', 'completed')
                ->avg('score') ?? 0;

            $examinee->avg_oral = $examinee->oralEvaluations()
                ->where('status', 'completed')
                ->avg('final_score') ?? 0;

            // الدرجة الإجمالية من 140 (40 مكتوب + 100 شفهي)
            $examinee->total_score = $examinee->avg_written + $examinee->avg_oral;

            // النسبة المئوية
            $examinee->percentage = ($examinee->total_score / 140) * 100;

            // هل نجح؟ (50% = 70 من 140)
            $examinee->is_passed = $examinee->percentage >= 50;
        }

        return view('reports.examinees', compact('examinees'));
    }

    /**
     * طباعة إيصال دخول الامتحان
     */
    public function printReceipt($id)
    {
        $examinee = Examinee::with(['narration', 'cluster', 'office', 'evaluations', 'oralEvaluations'])
            ->findOrFail($id);

        // حساب الدرجات
        $examinee->avg_written = $examinee->evaluations()
            ->where('status', 'completed')
            ->avg('score') ?? 0;

        $examinee->avg_oral = $examinee->oralEvaluations()
            ->where('status', 'completed')
            ->avg('final_score') ?? 0;

        $examinee->total_score = $examinee->avg_written + $examinee->avg_oral;
        $examinee->percentage = ($examinee->total_score / 140) * 100;
        $examinee->is_passed = $examinee->percentage >= 50;

        return view('reports.receipt', compact('examinee'));
    }
}