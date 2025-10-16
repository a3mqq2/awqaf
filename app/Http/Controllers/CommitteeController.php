<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\Cluster;
use App\Models\Narration;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommitteeController extends Controller
{
    /**
     * عرض قائمة اللجان
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Committee::with(['cluster', 'users', 'narrations', 'examinees']);

        // إذا كان مشرف لجنة، يعرض فقط اللجان التابعة لتجمعاته
        if ($user->hasRole('committee_supervisor')) {
            $query->whereIn('cluster_id', $user->clusters->pluck('id'));
        }

        // البحث
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلتر حسب التجمع
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        // فلتر حسب الرواية
        if ($request->filled('narration_id')) {
            $query->whereHas('narrations', function($q) use ($request) {
                $q->where('narrations.id', $request->narration_id);
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'examinees_desc':
                $query->withCount('examinees')->orderBy('examinees_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $committees = $query->paginate($request->get('per_page', 15));
        
        // التجمعات المتاحة للمستخدم
        if ($user->hasRole('committee_supervisor')) {
            $clusters = $user->clusters;
        } else {
            $clusters = Cluster::where('is_active', true)->get();
        }
        
        $narrations = Narration::where('is_active', true)->get();

        return view('committees.index', compact('committees', 'clusters', 'narrations'));
    }

    /**
     * عرض نموذج إنشاء لجنة جديدة
     */
    public function create()
    {
        $user = Auth::user();
        
        // التجمعات المخصصة للمستخدم فقط
        $clusters = $user->clusters;
        
        // إذا لم يكن لديه تجمعات
        if ($clusters->isEmpty()) {
            return redirect()->route('committees.index')
                           ->with('error', 'لا يمكنك إنشاء لجنة. يجب أن يكون لديك تجمع مخصص على الأقل.');
        }

        $narrations = Narration::where('is_active', true)->get();
        
        // المحكمين المتاحين
        $judges = User::role('judge')->where('is_active', true)->get();

        return view('committees.create', compact('clusters', 'narrations', 'judges'));
    }

    /**
     * حفظ لجنة جديدة
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'cluster_id' => [
                'required',
                'exists:clusters,id',
                function ($attribute, $value, $fail) use ($user) {
                    // التحقق من أن التجمع يتبع المستخدم
                    if (!$user->clusters->pluck('id')->contains($value)) {
                        $fail('التجمع المحدد غير مخصص لك.');
                    }
                },
            ],
            'narrations' => 'array',
            'narrations.*' => 'exists:narrations,id',
            'judges' => 'array',
            'judges.*' => 'exists:users,id',
        ]);

        $committee = Committee::create([
            'name' => $request->name,
            'cluster_id' => $request->cluster_id,
        ]);

        // ربط الروايات
        if ($request->filled('narrations')) {
            $committee->narrations()->sync($request->narrations);
        }

        // ربط المحكمين
        if ($request->filled('judges')) {
            $committee->users()->sync($request->judges);
        }

        // Log
        SystemLog::create([
            'description' => "تم إنشاء لجنة جديدة: {$committee->name} في التجمع: {$committee->cluster->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('committees.index')
                        ->with('success', 'تم إنشاء اللجنة بنجاح');
    }

    /**
     * عرض تفاصيل اللجنة
     */
    public function show(Committee $committee)
    {
        $user = Auth::user();
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($committee->cluster_id)) {
                abort(403, 'غير مصرح لك بعرض هذه اللجنة');
            }
        }
        
        $committee->load(['cluster', 'users', 'narrations', 'examinees']);
        
        return view('committees.show', compact('committee'));
    }

    /**
     * عرض نموذج تعديل اللجنة
     */
    public function edit(Committee $committee)
    {
        $user = Auth::user();
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($committee->cluster_id)) {
                abort(403, 'غير مصرح لك بتعديل هذه اللجنة');
            }
        }
        
        // التجمعات المخصصة للمستخدم فقط
        $clusters = $user->clusters;

        $narrations = Narration::where('is_active', true)->get();
        $judges = User::role('judge')->where('is_active', true)->get();
        
        $committee->load(['narrations', 'users']);

        return view('committees.edit', compact('committee', 'clusters', 'narrations', 'judges'));
    }

    /**
     * تحديث بيانات اللجنة
     */
    public function update(Request $request, Committee $committee)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'cluster_id' => [
                'required',
                'exists:clusters,id',
                function ($attribute, $value, $fail) use ($user) {
                    // التحقق من أن التجمع يتبع المستخدم
                    if (!$user->clusters->pluck('id')->contains($value)) {
                        $fail('التجمع المحدد غير مخصص لك.');
                    }
                },
            ],
            'narrations' => 'array',
            'narrations.*' => 'exists:narrations,id',
            'judges' => 'array',
            'judges.*' => 'exists:users,id',
        ]);

        $committee->update([
            'name' => $request->name,
            'cluster_id' => $request->cluster_id,
        ]);

        // تحديث الروايات
        $committee->narrations()->sync($request->input('narrations', []));

        // تحديث المحكمين
        $committee->users()->sync($request->input('judges', []));

        // Log
        SystemLog::create([
            'description' => "تم تعديل اللجنة: {$committee->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('committees.index')
                        ->with('success', 'تم تحديث اللجنة بنجاح');
    }

    /**
     * حذف اللجنة
     */
    public function destroy(Committee $committee)
    {
        $user = Auth::user();
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($committee->cluster_id)) {
                abort(403, 'غير مصرح لك بحذف هذه اللجنة');
            }
        }
        
        $name = $committee->name;
        
        $committee->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف اللجنة: {$name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('committees.index')
                        ->with('success', 'تم حذف اللجنة بنجاح');
    }
}