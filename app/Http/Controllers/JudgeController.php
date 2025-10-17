<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cluster;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class JudgeController extends Controller
{
    /**
     * عرض قائمة المحكمين
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // جلب المحكمين
        $query = User::role('judge')->with(['clusters']);

        // إذا كان مشرف لجنة، يعرض فقط المحكمين في تجمعاته
        if ($user->hasRole('committee_supervisor')) {
            $query->whereHas('clusters', function($q) use ($user) {
                $q->whereIn('clusters.id', $user->clusters->pluck('id'));
            });
        }

        // البحث
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // فلتر حسب الحالة
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        // فلتر حسب التجمع
        if ($request->filled('cluster_id')) {
            $query->whereHas('clusters', function($q) use ($request) {
                $q->where('clusters.id', $request->cluster_id);
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
            default:
                $query->latest();
                break;
        }

        $judges = $query->paginate($request->get('per_page', 15));
        
        // التجمعات المتاحة للمستخدم
        if ($user->hasRole('committee_supervisor')) {
            $clusters = $user->clusters;
        } else {
            $clusters = Cluster::where('is_active', true)->get();
        }

        return view('judges.index', compact('judges', 'clusters'));
    }

    /**
     * عرض نموذج إضافة محكم جديد
     */
    public function create()
    {
        $user = Auth::user();
        
        // التجمعات المخصصة للمستخدم فقط
        $clusters = $user->clusters;
        
        // إذا لم يكن لديه تجمعات
        if ($clusters->isEmpty()) {
            return redirect()->route('judges.index')
                           ->with('error', 'لا يمكنك إضافة محكم. يجب أن يكون لديك تجمع مخصص على الأقل.');
        }

        $permissions = Permission::whereIn('name', ['exam.scientific', 'exam.oral'])->get();
        return view('judges.create', compact('clusters','permissions'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'clusters' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($user) {
                    $userClusterIds = $user->clusters->pluck('id')->toArray();
                    $invalidClusters = array_diff($value, $userClusterIds);
                    if (!empty($invalidClusters)) {
                        $fail('بعض التجمعات المحددة غير مخصصة لك.');
                    }
                },
            ],
            'clusters.*' => 'exists:clusters,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);
    
        $judge = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);
    
        // إضافة الدور
        $judge->assignRole('judge');
    
        // ربط التجمعات
        $judge->clusters()->sync($request->clusters);
    
        // ربط الصلاحيات (لو تم تمريرها)
        if ($request->filled('permissions')) {
            $judge->givePermissionTo($request->permissions);
        }
    
        SystemLog::create([
            'description' => "تم إضافة محكم جديد: {$judge->name} ({$judge->email})",
            'user_id' => Auth::id(),
        ]);
    
        return redirect()->route('judges.index')
                        ->with('success', 'تم إضافة المحكم بنجاح');
    }
    
    /**
     * عرض تفاصيل المحكم
     */
    public function show(User $judge)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$judge->hasRole('judge')) {
            abort(404, 'المستخدم ليس محكماً');
        }
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            $hasAccess = $judge->clusters->pluck('id')->intersect($user->clusters->pluck('id'))->isNotEmpty();
            if (!$hasAccess) {
                abort(403, 'غير مصرح لك بعرض هذا المحكم');
            }
        }
        
        $judge->load(['clusters', 'committees']);
        
        return view('judges.show', compact('judge'));
    }

    /**
     * عرض نموذج تعديل المحكم
     */
    public function edit(User $judge)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$judge->hasRole('judge')) {
            abort(404, 'المستخدم ليس محكماً');
        }
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            $hasAccess = $judge->clusters->pluck('id')->intersect($user->clusters->pluck('id'))->isNotEmpty();
            if (!$hasAccess) {
                abort(403, 'غير مصرح لك بتعديل هذا المحكم');
            }
        }
        
        // التجمعات المخصصة للمستخدم فقط
        $clusters = $user->clusters;
        
        $judge->load(['clusters']);

        return view('judges.edit', compact('judge', 'clusters'));
    }

    /**
     * تحديث بيانات المحكم
     */
    public function update(Request $request, User $judge)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$judge->hasRole('judge')) {
            abort(404, 'المستخدم ليس محكماً');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($judge->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean',
            'clusters' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($user) {
                    // التحقق من أن جميع التجمعات تتبع المستخدم
                    $userClusterIds = $user->clusters->pluck('id')->toArray();
                    $invalidClusters = array_diff($value, $userClusterIds);
                    
                    if (!empty($invalidClusters)) {
                        $fail('بعض التجمعات المحددة غير مخصصة لك.');
                    }
                },
            ],
            'clusters.*' => 'exists:clusters,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $judge->update($userData);

        // تحديث التجمعات
        $judge->clusters()->sync($request->clusters);

        // Log
        SystemLog::create([
            'description' => "تم تعديل المحكم: {$judge->name} ({$judge->email})",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('judges.index')
                        ->with('success', 'تم تحديث المحكم بنجاح');
    }

    /**
     * حذف المحكم
     */
    public function destroy(User $judge)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$judge->hasRole('judge')) {
            abort(404, 'المستخدم ليس محكماً');
        }
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            $hasAccess = $judge->clusters->pluck('id')->intersect($user->clusters->pluck('id'))->isNotEmpty();
            if (!$hasAccess) {
                abort(403, 'غير مصرح لك بحذف هذا المحكم');
            }
        }
        
        $name = $judge->name;
        $email = $judge->email;
        
        $judge->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف المحكم: {$name} ({$email})",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('judges.index')
                        ->with('success', 'تم حذف المحكم بنجاح');
    }

    /**
     * تغيير حالة المحكم
     */
    public function toggleStatus(User $judge)
    {
        $user = Auth::user();
        
        // التحقق من أن المستخدم محكم
        if (!$judge->hasRole('judge')) {
            abort(404, 'المستخدم ليس محكماً');
        }
        
        // التحقق من الصلاحية
        if ($user->hasRole('committee_supervisor')) {
            $hasAccess = $judge->clusters->pluck('id')->intersect($user->clusters->pluck('id'))->isNotEmpty();
            if (!$hasAccess) {
                abort(403, 'غير مصرح لك بتغيير حالة هذا المحكم');
            }
        }
        
        $judge->is_active = !$judge->is_active;
        $judge->save();

        $status = $judge->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        // Log
        SystemLog::create([
            'description' => "تم {$status} المحكم: {$judge->name} ({$judge->email})",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('judges.index')
                        ->with('success', 'تم تحديث حالة المحكم بنجاح');
    }
}