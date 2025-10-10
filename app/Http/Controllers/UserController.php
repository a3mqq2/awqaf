<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cluster;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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

        $users = $query->paginate(10);
        $clusters = Cluster::all();

        return view('users.index', compact('users', 'clusters'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $clusters = Cluster::all();
        return view('users.create', compact('permissions', 'clusters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean',
            'clusters' => 'array',
            'clusters.*' => 'exists:clusters,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->syncPermissions($request->input('permissions', []));
        $user->clusters()->sync($request->input('clusters', []));

        // Log
        SystemLog::create([
            'description' => "تم إنشاء مستخدم جديد: {$user->name} ({$user->email})",
            'user_id'     => Auth::id(),
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
        $permissions = Permission::all();
        $clusters = Cluster::all();
        $user->load(['permissions', 'clusters']);
        return view('users.edit', compact('user', 'permissions', 'clusters'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required','string','email','max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'sometimes|boolean',
            'clusters' => 'array',
            'clusters.*' => 'exists:clusters,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active', false),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $user->syncPermissions($request->input('permissions', []));
        $user->clusters()->sync($request->input('clusters', []));

        // Log
        SystemLog::create([
            'description' => "تم تعديل المستخدم: {$user->name} ({$user->email})",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'لا يمكنك حذف حسابك الخاص');
        }

        $name = $user->name;
        $email = $user->email;

        $user->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف المستخدم: {$name} ({$email})",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleStatus(User $user)
    {
        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        // Log
        SystemLog::create([
            'description' => "تم {$status} المستخدم: {$user->name} ({$user->email})",
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'تم تحديث حالة المستخدم بنجاح');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $userIds = $request->users;
        $currentUserId = auth()->id();
        $message = '';

        switch ($request->action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['is_active' => true]);
                $message = 'تم تفعيل المستخدمين المحددين بنجاح';

                SystemLog::create([
                    'description' => "تم تفعيل مجموعة من المستخدمين (IDs: " . implode(',', $userIds) . ")",
                    'user_id'     => $currentUserId,
                ]);
                break;

            case 'deactivate':
                $filteredUserIds = array_diff($userIds, [$currentUserId]);
                User::whereIn('id', $filteredUserIds)->update(['is_active' => false]);
                $message = 'تم إلغاء تفعيل المستخدمين المحددين بنجاح';

                SystemLog::create([
                    'description' => "تم إلغاء تفعيل مجموعة من المستخدمين (IDs: " . implode(',', $filteredUserIds) . ")",
                    'user_id'     => $currentUserId,
                ]);
                break;

            case 'delete':
                $filteredUserIds = array_diff($userIds, [$currentUserId]);
                User::whereIn('id', $filteredUserIds)->delete();
                $message = 'تم حذف المستخدمين المحددين بنجاح';

                SystemLog::create([
                    'description' => "تم حذف مجموعة من المستخدمين (IDs: " . implode(',', $filteredUserIds) . ")",
                    'user_id'     => $currentUserId,
                ]);
                break;
        }

        return redirect()->route('users.index')->with('success', $message);
    }
}
