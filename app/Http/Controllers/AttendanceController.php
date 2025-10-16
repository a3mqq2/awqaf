<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\Committee;
use App\Models\Cluster;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * عرض صفحة تسجيل الحضور
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // جلب التجمعات المتاحة
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            $clusters = $user->clusters;
        } else {
            $clusters = Cluster::where('is_active', true)->get();
        }

        // جلب اللجان حسب التجمع المحدد
        $committees = collect();
        if ($request->filled('cluster_id')) {
            $committees = Committee::where('cluster_id', $request->cluster_id)->get();
        }

        return view('attendance.index', compact('clusters', 'committees'));
    }

    /**
     * البحث عن ممتحن بالرقم الوطني
     */
    public function search(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string',
        ]);

        $user = Auth::user();

        // البحث عن الممتحن
        $query = Examinee::where(function($q) use ($request) {
                $q->where('national_id', $request->national_id)
                  ->orWhere('passport_no', $request->national_id);
            })
            ->where('status', 'confirmed')
            ->with(['cluster', 'committee', 'narration']);

        // إذا كان المستخدم كنترول أو مشرف لجنة، يعرض فقط ممتحني تجمعاته
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            $query->whereIn('cluster_id', $user->clusters->pluck('id'));
        }

        $examinee = $query->first();

        if (!$examinee) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على ممتحن بهذا الرقم، أو أن حالته غير مؤكدة'
            ]);
        }

        return response()->json([
            'success' => true,
            'examinee' => [
                'id' => $examinee->id,
                'full_name' => $examinee->full_name,
                'national_id' => $examinee->national_id ?? $examinee->passport_no,
                'phone' => $examinee->phone,
                'cluster' => $examinee->cluster ? $examinee->cluster->name : 'غير محدد',
                'committee' => $examinee->committee ? $examinee->committee->name : 'غير محدد',
                'narration' => $examinee->narration ? $examinee->narration->name : 'غير محدد',
                'is_attended' => $examinee->is_attended,
                'attended_at' => $examinee->attended_at ? $examinee->attended_at->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }

    /**
     * تسجيل حضور الممتحن
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'examinee_id' => 'required|exists:examinees,id',
        ]);

        $user = Auth::user();
        $examinee = Examinee::findOrFail($request->examinee_id);

        // التحقق من الصلاحية
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($examinee->cluster_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بتسجيل حضور هذا الممتحن'
                ], 403);
            }
        }

        // التحقق من أن الممتحن مؤكد
        if ($examinee->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تسجيل الحضور. حالة الممتحن غير مؤكدة'
            ]);
        }

        // تسجيل الحضور - فقط تغيير الحالة
        $examinee->status = 'attended';
        $examinee->is_attended = true;
        $examinee->attended_at = now();
        $examinee->save();

        // Log
        SystemLog::create([
            'description' => "تم تسجيل حضور الممتحن: {$examinee->full_name} (الرقم الوطني: " . ($examinee->national_id ?? $examinee->passport_no) . ")",
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الحضور بنجاح',
            'attended_at' => $examinee->attended_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * إلغاء حضور الممتحن
     */
    public function cancelAttendance(Request $request)
    {
        $request->validate([
            'examinee_id' => 'required|exists:examinees,id',
        ]);

        $user = Auth::user();
        $examinee = Examinee::findOrFail($request->examinee_id);

        // التحقق من الصلاحية
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($examinee->cluster_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بإلغاء حضور هذا الممتحن'
                ], 403);
            }
        }

        // إلغاء الحضور - إرجاع الحالة لمؤكد
        $examinee->status = 'confirmed';
        $examinee->is_attended = false;
        $examinee->attended_at = null;
        $examinee->save();

        // Log
        SystemLog::create([
            'description' => "تم إلغاء حضور الممتحن: {$examinee->full_name} (الرقم الوطني: " . ($examinee->national_id ?? $examinee->passport_no) . ")",
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إلغاء الحضور بنجاح'
        ]);
    }

    /**
     * عرض تقرير الحضور للجنة
     */
    public function report(Request $request)
    {
        $request->validate([
            'committee_id' => 'required|exists:committees,id',
        ]);

        $user = Auth::user();
        $committee = Committee::with(['examinees.narration', 'cluster'])->findOrFail($request->committee_id);

        // التحقق من الصلاحية
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($committee->cluster_id)) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه اللجنة');
            }
        }

        // إحصائيات الحضور
        $totalExaminees = $committee->examinees()->where('status', '!=', 'withdrawn')->count();
        $attendedCount = $committee->examinees()->where('status', 'attended')->count();
        $notAttendedCount = $totalExaminees - $attendedCount;
        $attendancePercentage = $totalExaminees > 0 ? round(($attendedCount / $totalExaminees) * 100, 2) : 0;

        // قائمة الممتحنين
        $examinees = $committee->examinees()
            ->where('status', '!=', 'withdrawn')
            ->with('narration')
            ->orderByRaw("CASE WHEN status = 'attended' THEN 0 ELSE 1 END")
            ->orderBy('attended_at', 'desc')
            ->paginate(20);

        return view('attendance.report', compact(
            'committee',
            'totalExaminees',
            'attendedCount',
            'notAttendedCount',
            'attendancePercentage',
            'examinees'
        ));
    }

    /**
     * طباعة تقرير الحضور
     */
    public function printReport(Request $request)
    {
        $request->validate([
            'committee_id' => 'required|exists:committees,id',
        ]);

        $user = Auth::user();
        $committee = Committee::with(['examinees.narration', 'cluster'])->findOrFail($request->committee_id);

        // التحقق من الصلاحية
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($committee->cluster_id)) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه اللجنة');
            }
        }

        // إحصائيات الحضور
        $totalExaminees = $committee->examinees()->where('status', '!=', 'withdrawn')->count();
        $attendedCount = $committee->examinees()->where('status', 'attended')->count();
        $notAttendedCount = $totalExaminees - $attendedCount;
        $attendancePercentage = $totalExaminees > 0 ? round(($attendedCount / $totalExaminees) * 100, 2) : 0;

        // قائمة الممتحنين
        $examinees = $committee->examinees()
            ->where('status', '!=', 'withdrawn')
            ->with('narration')
            ->orderByRaw("CASE WHEN status = 'attended' THEN 0 ELSE 1 END")
            ->orderBy('attended_at', 'desc')
            ->get();

        return view('attendance.print', compact(
            'committee',
            'totalExaminees',
            'attendedCount',
            'notAttendedCount',
            'attendancePercentage',
            'examinees'
        ));
    }
}