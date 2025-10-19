<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cluster;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'permissions', 'clusters']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->filled('cluster_id')) {
            $query->whereHas('clusters', function ($q) use ($request) {
                $q->where('clusters.id', $request->cluster_id);
            });
        }

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

        $users = $query->paginate(15);
        $clusters = Cluster::all();
        $roles = Role::whereIn('name',[
            'committee_control','committee_supervisor','admin',
        ])->get();

        return view('users.index', compact('users', 'clusters', 'roles'));
    }

    public function create()
    {
        $roles = Role::whereIn('name',[
            'committee_control','committee_supervisor','admin',
        ])->get();
        $permissions = Permission::all();
        $clusters = Cluster::all();
        
        return view('users.create', compact('roles', 'permissions', 'clusters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean',
            'role' => 'required|exists:roles,name',
            'clusters' => 'array',
            'clusters.*' => 'exists:clusters,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // إعطاء الـ Role
        $user->assignRole($request->role);

        // إعطاء صلاحيات إضافية (اختياري)
        if ($request->filled('permissions')) {
            $user->givePermissionTo($request->permissions);
        }

        // ربط التجمعات
        $user->clusters()->sync($request->input('clusters', []));

        // Log
        SystemLog::create([
            'description' => "تم إنشاء مستخدم جديد: {$user->name} ({$user->email}) - الدور: {$request->role}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions', 'clusters']);
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('name',[
            'committee_control','committee_supervisor','admin',
        ])->get();
        $permissions = Permission::all();
        $clusters = Cluster::all();
        $user->load(['roles', 'permissions', 'clusters']);
        
        return view('users.edit', compact('user', 'roles', 'permissions', 'clusters'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
            'clusters' => 'array',
            'clusters.*' => 'exists:clusters,id',
        ]);
    
        // تحديث البيانات الأساسية
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', false),
        ];
    
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
    
        $user->update($userData);
    
        // تحديث الصلاحيات فقط بدون أدوار
        $user->syncPermissions($request->input('permissions', []));
    
        // تحديث التجمعات
        $user->clusters()->sync($request->input('clusters', []));
    
        // تسجيل في السجلات
        SystemLog::create([
            'description' => "تم تعديل المستخدم: {$user->name} ({$user->email}) وتحديث صلاحياته مباشرة بدون أدوار.",
            'user_id' => Auth::id(),
        ]);
    
        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }
    
    

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $name = $user->name;
        $email = $user->email;

        $user->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف المستخدم: {$name} ({$email})",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        // Log
        SystemLog::create([
            'description' => "تم {$status} المستخدم: {$user->name} ({$user->email})",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم تحديث حالة المستخدم بنجاح');
    }
}