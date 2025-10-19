<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Drawing;
use App\Models\Examinee;
use App\Models\Narration;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ExamineesExport;
use App\Imports\ExamineesImport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExamineeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);
    
        // Apply cluster filter based on user permissions
        if (!empty($userClusterIds)) {
            $query->whereIn('cluster_id', $userClusterIds);
        }
    
        // Search by name (all name fields)
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $searchTerm = $request->name;
                $q->where('first_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('father_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('grandfather_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('full_name', 'like', '%'.$searchTerm.'%');
            });
        }
    
        // Filter by national ID
        if ($request->filled('national_id')) {
            $query->where('national_id', 'like', '%'.$request->national_id.'%');
        }
    
        // Filter by passport number
        if ($request->filled('passport_no')) {
            $query->where('passport_no', 'like', '%'.$request->passport_no.'%');
        }
    
        // Filter by phone
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%'.$request->phone.'%');
        }
    
        // Filter by WhatsApp
        if ($request->filled('whatsapp')) {
            $query->where('whatsapp', 'like', '%'.$request->whatsapp.'%');
        }
    
        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
    
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Filter by nationality
        if ($request->filled('nationality')) {
            $query->where('nationality', 'like', '%'.$request->nationality.'%');
        }
    
        // Filter by office (multiple selection)
        if ($request->filled('office_id')) {
            $query->whereIn('office_id', (array)$request->office_id);
        }
    
        // Filter by cluster (multiple selection with user permission check)
        if ($request->filled('cluster_id')) {
            $clusterIds = (array)$request->cluster_id;
            
            // If user has limited cluster access, intersect with their allowed clusters
            if (!empty($userClusterIds)) {
                $clusterIds = array_intersect($clusterIds, $userClusterIds);
            }
            
            if (!empty($clusterIds)) {
                $query->whereIn('cluster_id', $clusterIds);
            }
        }
    
        // Filter by narration (multiple selection)
        if ($request->filled('narration_id')) {
            $query->whereIn('narration_id', (array)$request->narration_id);
        }
    
        // Filter by drawing (multiple selection)
        if ($request->filled('drawing_id')) {
            $query->whereIn('drawing_id', (array)$request->drawing_id);
        }
    
        // Filter by current residence
        if ($request->filled('current_residence')) {
            $query->where('current_residence', 'like', '%'.$request->current_residence.'%');
        }
    
        // Filter by birth date range
        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->birth_date_from);
        }
    
        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->birth_date_to);
        }
    
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        // Validate sort fields
        $allowedSortFields = [
            'created_at', 'first_name', 'last_name', 'full_name', 'national_id', 
            'birth_date', 'status', 'gender', 'cluster_id', 'office_id', 
            'narration_id', 'drawing_id'
        ];
        
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
    
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
    
        $query->orderBy($sortBy, $sortDirection);
    
        // Pagination
        $perPage = $request->get('per_page', 15);
        
        if ($perPage == 'all') {
            $examinees = $query->get();
            // Create a custom pagination-like object for consistency
            $examinees = new \Illuminate\Pagination\LengthAwarePaginator(
                $examinees,
                $examinees->count(),
                $examinees->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Validate per_page value
            $perPage = in_array($perPage, [15, 25, 50, 100]) ? $perPage : 15;
            $examinees = $query->paginate($perPage)->appends($request->query());
        }
        
        // Load data for filters - only user's allowed clusters
        $offices = Office::where('is_active', true)->get();
        
        // Show only clusters assigned to the user
        if (!empty($userClusterIds)) {
            $clusters = Cluster::where('is_active', true)
                ->whereIn('id', $userClusterIds)
                ->get();
        } else {
            // If no specific clusters assigned, show all (Admin)
            $clusters = Cluster::where('is_active', true)->get();
        }
        
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
    
        return view('examinees.index', compact('examinees', 'offices', 'clusters', 'narrations', 'drawings'));
    }

    public function create()
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $offices = Office::all();
        
        if (!empty($userClusterIds)) {
            $clusters = Cluster::whereIn('id', $userClusterIds)->get();
        } else {
            $clusters = Cluster::all();
        }
        
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
        
        return view('examinees.create', compact('offices', 'clusters', 'narrations', 'drawings'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50|unique:examinees,national_id',
            'passport_no' => 'nullable|string|max:50|unique:examinees,passport_no',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'current_residence' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'office_id' => 'required|exists:offices,id',
            'cluster_id' => 'required|exists:clusters,id',
            'narration_id' => 'required|exists:narrations,id',
            'drawing_id' => 'required|exists:drawings,id',
            'status' => 'required|in:confirmed,pending,withdrawn',
            'notes' => 'nullable|string',
        ]);

        // التحقق من صلاحية إضافة الممتحن لهذا الـ cluster
        if (!empty($userClusterIds) && !in_array($data['cluster_id'], $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لإضافة ممتحن في هذا التجمع');
        }

        // Generate full_name
        $data['full_name'] = trim(
            ($data['first_name'] ?? '') . ' ' . 
            ($data['father_name'] ?? '') . ' ' . 
            ($data['grandfather_name'] ?? '') . ' ' . 
            ($data['last_name'] ?? '')
        );

        $examinee = Examinee::create($data);




        SystemLog::create([
            'description' => "قام المستخدم بإضافة ممتحن جديد: {$examinee->full_name} (رقم وطني: {$examinee->national_id})",
            'user_id'     => Auth::id(),
        ]);


        return redirect()->route('examinees.index')
            ->with('success', 'تم إضافة الممتحن بنجاح');
    }

    public function show(Examinee $examinee)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // التحقق من صلاحية عرض الممتحن
        if (!empty($userClusterIds) && !in_array($examinee->cluster_id, $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذا الممتحن');
        }
        
        $examinee->load(['office', 'cluster', 'narration', 'drawing']);
        
        return view('examinees.show', compact('examinee'));
    }

    public function edit(Examinee $examinee)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // التحقق من صلاحية تعديل الممتحن
        if (!empty($userClusterIds) && !in_array($examinee->cluster_id, $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الممتحن');
        }
        
        $offices = Office::all();
        
        // إظهار فقط الـ clusters المخصصة للمستخدم
        if (!empty($userClusterIds)) {
            $clusters = Cluster::whereIn('id', $userClusterIds)->get();
        } else {
            $clusters = Cluster::all();
        }
        
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
        
        return view('examinees.edit', compact('examinee', 'offices', 'clusters', 'narrations', 'drawings'));
    }

    public function update(Request $request, Examinee $examinee)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        if (!empty($userClusterIds) && !in_array($examinee->cluster_id, $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الممتحن');
        }
        
        $data = $request->validate([
            'first_name'       => 'required|string|max:255',
            'father_name'      => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name'        => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:255',
            'national_id'      => 'nullable|string|max:50|unique:examinees,national_id,'.$examinee->id,
            'passport_no'      => 'nullable|string|max:50',
            'phone'            => 'nullable|string|max:20',
            'whatsapp'         => 'nullable|string|max:20',
            'current_residence'=> 'nullable|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'birth_date'       => 'nullable|date',
            'office_id'        => 'required|exists:offices,id',
            'cluster_id'       => 'required|exists:clusters,id',
            'narration_id'     => 'required|exists:narrations,id',
            'drawing_id'       => 'required|exists:drawings,id',
            'status'           => 'required|in:confirmed,pending,withdrawn,under_review,rejected',
            'notes'            => 'nullable|string',
        ]);
    
        // إعادة توليد full_name
        $data['full_name'] = trim(
            ($data['first_name'] ?? '') . ' ' . 
            ($data['father_name'] ?? '') . ' ' . 
            ($data['grandfather_name'] ?? '') . ' ' . 
            ($data['last_name'] ?? '')
        );
    
        // حفظ القيم القديمة
        $oldValues = $examinee->getOriginal();
    
        // تحديث البيانات
        $examinee->update($data);
    
        // ترجمات الحقول
        $fieldLabels = [
            'first_name'        => 'الاسم الأول',
            'father_name'       => 'اسم الأب',
            'grandfather_name'  => 'اسم الجد',
            'last_name'         => 'اللقب',
            'full_name'         => 'الاسم الكامل',
            'nationality'       => 'الجنسية',
            'national_id'       => 'الرقم الوطني',
            'passport_no'       => 'رقم الجواز',
            'phone'             => 'رقم الهاتف',
            'whatsapp'          => 'الواتساب',
            'current_residence' => 'مكان الإقامة',
            'gender'            => 'الجنس',
            'birth_date'        => 'تاريخ الميلاد',
            'office_id'         => 'المكتب',
            'cluster_id'        => 'التجمع',
            'narration_id'      => 'الرواية',
            'drawing_id'        => 'الرسم',
            'status'            => 'الحالة',
            'notes'             => 'الملاحظات',
        ];
    
        // ترجمة الحالات للعربية
        $statusLabels = [
            'pending'      => 'قيد الانتظار',
            'withdrawn'    => 'منسحب',
            'confirmed'    => 'مؤكد',
            'under_review' => 'قيد المراجعة',
            'rejected'     => 'مرفوض',
        ];
    
        // ترجمة الجنس للعربية
        $genderLabels = [
            'male'   => 'ذكر',
            'female' => 'أنثى',
        ];
    
        // الموديلات المرتبطة بالحقل
        $relationNames = [
            'office_id'   => \App\Models\Office::class,
            'cluster_id'  => \App\Models\Cluster::class,
            'narration_id'=> \App\Models\Narration::class,
            'drawing_id'  => \App\Models\Drawing::class,
        ];
    
        // مقارنة القيم القديمة والجديدة
        $changes = [];
        foreach ($data as $field => $newValue) {
            $oldValue = $oldValues[$field] ?? null;
    
            // توحيد صيغة التاريخ قبل المقارنة
            if ($field == 'birth_date') {
                $oldValue = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('Y-m-d') : null;
                $newValue = $newValue ? \Carbon\Carbon::parse($newValue)->format('Y-m-d') : null;
            }
    
            // إذا الحقل من نوع *_id نجيب الاسم من العلاقة
            if (array_key_exists($field, $relationNames)) {
                $modelClass = $relationNames[$field];
                $oldName = $oldValue ? ($modelClass::find($oldValue)->name ?? null) : null;
                $newName = $newValue ? ($modelClass::find($newValue)->name ?? null) : null;
                $oldValue = $oldName;
                $newValue = $newName;
            }
    
            // ترجمة الحالة
            if ($field == 'status') {
                $oldValue = $statusLabels[$oldValue] ?? $oldValue;
                $newValue = $statusLabels[$newValue] ?? $newValue;
            }
    
            // ترجمة الجنس
            if ($field == 'gender') {
                $oldValue = $genderLabels[$oldValue] ?? $oldValue;
                $newValue = $genderLabels[$newValue] ?? $newValue;
            }
    
            // فقط نسجل التغيير الحقيقي
            if ($oldValue != $newValue) {
                $label = $fieldLabels[$field] ?? $field;
                $changes[] = "تم تغيير [{$label}] من '{$oldValue}' إلى '{$newValue}'";
            }
        }
    
        if (!empty($changes)) {
            \App\Models\SystemLog::create([
                'description' => "قام المستخدم بتعديل بيانات الممتحن: {$examinee->full_name} (ID: {$examinee->id})\n"
                               . implode("\n", $changes),
                'user_id'     => $user->id,
            ]);
        }
    
        return redirect()->route('examinees.show', $examinee)
            ->with('success', 'تم تحديث بيانات الممتحن بنجاح');
    }
    
    

    public function destroy(Examinee $examinee)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // التحقق من صلاحية حذف الممتحن
        if (!empty($userClusterIds) && !in_array($examinee->cluster_id, $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لحذف هذا الممتحن');
        }
    
        $name = $examinee->full_name;
        $id   = $examinee->id;
    
        $examinee->delete();
    
        // إضافة سجل بسيط في system_logs
        \App\Models\SystemLog::create([
            'description' => "تم حذف الممتحن: {$name} (ID: {$id})",
            'user_id'     => $user->id,
        ]);
    
        return redirect()->route('examinees.index')
            ->with('success', 'تم حذف الممتحن بنجاح');
    }
    

    public function print(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);
    
        // Apply cluster filter - from request or user's clusters
        if ($request->filled('cluster_id')) {
            $requestedClusterIds = (array)$request->cluster_id;
            
            // If user has cluster restrictions, intersect with requested clusters
            if (!empty($userClusterIds)) {
                $allowedClusterIds = array_intersect($requestedClusterIds, $userClusterIds);
                if (!empty($allowedClusterIds)) {
                    $query->whereIn('cluster_id', $allowedClusterIds);
                } else {
                    // Requested clusters not in user's allowed clusters - return empty
                    $query->whereRaw('1 = 0');
                }
            } else {
                // No user restrictions, use requested clusters
                $query->whereIn('cluster_id', $requestedClusterIds);
            }
        } else {
            // No cluster_id in request, use user's clusters
            if (!empty($userClusterIds)) {
                $query->whereIn('cluster_id', $userClusterIds);
            }
        }
    
        // Apply same filters as index
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $searchTerm = $request->name;
                $q->where('first_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('father_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('grandfather_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('full_name', 'like', '%'.$searchTerm.'%');
            });
        }
    
        if ($request->filled('national_id')) {
            $query->where('national_id', 'like', '%'.$request->national_id.'%');
        }
    
        if ($request->filled('passport_no')) {
            $query->where('passport_no', 'like', '%'.$request->passport_no.'%');
        }
    
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%'.$request->phone.'%');
        }
    
        if ($request->filled('whatsapp')) {
            $query->where('whatsapp', 'like', '%'.$request->whatsapp.'%');
        }
    
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('nationality')) {
            $query->where('nationality', 'like', '%'.$request->nationality.'%');
        }
    
        // Multiple filters
        if ($request->filled('office_id')) {
            $query->whereIn('office_id', (array)$request->office_id);
        }
    
        if ($request->filled('narration_id')) {
            $query->whereIn('narration_id', (array)$request->narration_id);
        }
    
        if ($request->filled('drawing_id')) {
            $query->whereIn('drawing_id', (array)$request->drawing_id);
        }
    
        if ($request->filled('current_residence')) {
            $query->where('current_residence', 'like', '%'.$request->current_residence.'%');
        }
    
        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->birth_date_from);
        }
    
        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->birth_date_to);
        }
    
        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSortFields = [
            'created_at', 'first_name', 'last_name', 'full_name', 'national_id', 
            'birth_date', 'status', 'gender', 'cluster_id', 'office_id', 
            'narration_id', 'drawing_id'
        ];
        
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
    
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
    
        $query->orderBy($sortBy, $sortDirection);
    
        $examinees = $query->get();
    
        // Get selected columns (default to all if not specified)
        $columns = $request->input('columns', [
            'id', 'full_name', 'national_id', 'passport_no', 'phone', 
            'nationality', 'office', 'cluster', 'narration', 'drawing', 'status'
        ]);
    
        if(isset($user))
        {
            \App\Models\SystemLog::create([
                'description' => "تم طباعة كشف ممتحنين",
                'user_id'     => $user->id,
            ]);
        }
    
        return view('examinees.print', compact('examinees', 'columns'));
    }
    
    public function importForm()
    {
        return view('examinees.import');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
    
        Excel::import(new ExamineesImport, $request->file('file'));
        \App\Models\SystemLog::create([
            'description' => "تم استيراد بيانات من الإكسل",
            'user_id'     => auth()->id(),
        ]);
    
        return redirect()->route('examinees.index')
            ->with('success', 'تم استيراد الممتحنين وجميع محاولاتهم بنجاح');
    }


    public function printCards(Request $request)
    {
        $ids = explode(',', $request->ids);
        
        $examinees = Examinee::with(['cluster', 'narration', 'drawing'])
            ->whereIn('id', $ids)
            ->get();

            if(auth()->check())
            {
                \App\Models\SystemLog::create([
                    'description' => "تم طباعة كروت ممتحنين",
                    'user_id'     => auth()->id(),
                ]);
            }
            
        
        return view('examinees.card-print', compact('examinees'));
    }


/**
     * Approve examinee (change status to confirmed)
     */
    public function approve(Examinee $examinee)
    {
        $examinee->update([
            'status' => 'confirmed'
        ]);

        \App\Models\SystemLog::create([
            'description' => "تم قبول ممتحن: {$examinee->full_name} (ID: {$examinee->id})",
            'user_id'     => auth()->id(),
        ]);


        return redirect()->route('examinees.index')
            ->with('success', 'تم قبول الممتحن بنجاح');
    }

    /**
     * Reject examinee with reason
     */
    public function reject(Request $request, Examinee $examinee)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // Check permission
        if (!empty($userClusterIds) && !in_array($examinee->cluster_id, $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لرفض هذا الممتحن');
        }
        
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);
    
        $examinee->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);
    

        \App\Models\SystemLog::create([
            'description' => "تم رفض ممتحن: {$examinee->full_name} (ID: {$examinee->id})",
            'user_id'     => $user->id,
        ]);

        
        return redirect()->route('examinees.index')
            ->with('success', 'تم رفض الممتحن بنجاح');
    }
    
    public function printOptions(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        // Get filtered data count
        $query = Examinee::query();
        
        if (!empty($userClusterIds)) {
            $query->whereIn('cluster_id', $userClusterIds);
        }
        
        // Apply all filters from request
        $this->applyFilters($query, $request);
        
        $count = $query->count();
        
        return view('examinees.print-options', compact('count'));
    }
    
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);
        
        if (!empty($userClusterIds)) {
            $query->whereIn('cluster_id', $userClusterIds);
        }
        
        $this->applyFilters($query, $request);
        
        $examinees = $query->get();
        
        // Get selected columns
        $columns = $request->input('columns', [
            'id', 'full_name', 'national_id', 'phone', 'cluster', 'office', 'narration', 'drawing', 'status'
        ]);
        
        \App\Models\SystemLog::create([
            'description' => "تم تصدير كشف ممتحنين PDF",
            'user_id' => $user->id,
        ]);
        
        $pdf = Pdf::loadView('examinees.pdf-export', compact('examinees', 'columns'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('examinees-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);
        
        if (!empty($userClusterIds)) {
            $query->whereIn('cluster_id', $userClusterIds);
        }
        
        $this->applyFilters($query, $request);
        
        $examinees = $query->get();
        
        // Get selected columns
        $columns = $request->input('columns', [
            'id', 'full_name', 'national_id', 'phone', 'cluster', 'office', 'narration', 'drawing', 'status'
        ]);
        
        \App\Models\SystemLog::create([
            'description' => "تم تصدير كشف ممتحنين Excel",
            'user_id' => $user->id,
        ]);
        
        return Excel::download(new ExamineesExport($examinees, $columns), 'examinees-' . now()->format('Y-m-d') . '.xlsx');
    }
    
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $searchTerm = $request->name;
                $q->where('first_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('father_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('grandfather_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('last_name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('full_name', 'like', '%'.$searchTerm.'%');
            });
        }
        
        if ($request->filled('national_id')) {
            $query->where('national_id', 'like', '%'.$request->national_id.'%');
        }
        
        if ($request->filled('passport_no')) {
            $query->where('passport_no', 'like', '%'.$request->passport_no.'%');
        }
        
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%'.$request->phone.'%');
        }
        
        if ($request->filled('whatsapp')) {
            $query->where('whatsapp', 'like', '%'.$request->whatsapp.'%');
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('nationality')) {
            $query->where('nationality', 'like', '%'.$request->nationality.'%');
        }
        
        if ($request->filled('office_id')) {
            $query->whereIn('office_id', (array)$request->office_id);
        }
        
        if ($request->filled('cluster_id')) {
            $query->whereIn('cluster_id', (array)$request->cluster_id);
        }
        
        if ($request->filled('narration_id')) {
            $query->whereIn('narration_id', (array)$request->narration_id);
        }
        
        if ($request->filled('drawing_id')) {
            $query->whereIn('drawing_id', (array)$request->drawing_id);
        }
        
        if ($request->filled('current_residence')) {
            $query->where('current_residence', 'like', '%'.$request->current_residence.'%');
        }
        
        if ($request->filled('birth_date_from')) {
            $query->whereDate('birth_date', '>=', $request->birth_date_from);
        }
        
        if ($request->filled('birth_date_to')) {
            $query->whereDate('birth_date', '<=', $request->birth_date_to);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
    }
}