<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    /**
     * عرض جميع السجلات مع الفلاتر
     */
    public function index(Request $request)
    {
        $query = SystemLog::with('user');

        // البحث بالنص داخل الوصف
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // فلترة حسب التاريخ (من)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // فلترة حسب التاريخ (إلى)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ترتيب النتائج
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        // عدد السجلات في الصفحة
        $perPage = $request->get('per_page', 15);

        $logs = $query->paginate($perPage)->appends($request->query());

        $users = User::all();

        return view('system_logs.index', compact('logs', 'users'));
    }

    /**
     * عرض سجل واحد
     */
    public function show(SystemLog $systemLog)
    {
        $systemLog->load('user');
        return view('system_logs.show', compact('systemLog'));
    }

    /**
     * حذف سجل
     */
    public function destroy(SystemLog $systemLog)
    {
        $systemLog->delete();
        return redirect()->route('system_logs.index')->with('success', 'تم حذف السجل بنجاح');
    }

    /**
     * حذف كل السجلات
     */
    public function clear()
    {
        SystemLog::truncate();
        return redirect()->route('system_logs.index')->with('success', 'تم مسح جميع السجلات بنجاح');
    }
}
