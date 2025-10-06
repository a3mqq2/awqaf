<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Cluster;
use App\Models\Drawing;
use App\Models\Examinee;
use App\Models\Narration;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Imports\ExamineesImport;
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
        
        if ($perPage === 'all') {
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
        
        // إظهار فقط الـ clusters المخصصة للمستخدم
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

        // Handle exam attempts
        if ($request->has('exam_attempts')) {
            foreach ($request->exam_attempts as $attempt) {
                if (!empty($attempt['year']) || !empty($attempt['narration_id']) || !empty($attempt['drawing_id'])) {
                    $examinee->examAttempts()->create([
                        'year' => $attempt['year'] ?? null,
                        'narration_id' => $attempt['narration_id'] ?? null,
                        'drawing_id' => $attempt['drawing_id'] ?? null,
                        'side' => $attempt['side'] ?? null,
                        'result' => $attempt['result'] ?? null,
                        'percentage' => $attempt['percentage'] ?? null,
                    ]);
                }
            }
        }

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
        
        $examinee->load(['office', 'cluster', 'narration', 'drawing', 'examAttempts.narration', 'examAttempts.drawing']);
        
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
        $examinee->load('examAttempts');
        
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
            'first_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50|unique:examinees,national_id,'.$examinee->id,
            'passport_no' => 'nullable|string|max:50',
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

        // التحقق من صلاحية تغيير الـ cluster
        if (!empty($userClusterIds) && !in_array($data['cluster_id'], $userClusterIds)) {
            abort(403, 'ليس لديك صلاحية لنقل الممتحن إلى هذا التجمع');
        }

        // Generate full_name
        $data['full_name'] = trim(
            ($data['first_name'] ?? '') . ' ' . 
            ($data['father_name'] ?? '') . ' ' . 
            ($data['grandfather_name'] ?? '') . ' ' . 
            ($data['last_name'] ?? '')
        );

        $examinee->update($data);

        $examinee->examAttempts()->delete();

        if ($request->has('exam_attempts')) {
            foreach ($request->exam_attempts as $attempt) {
                if (!empty($attempt['year']) || !empty($attempt['narration_id']) || !empty($attempt['drawing_id'])) {
                    $examinee->examAttempts()->create([
                        'year' => $attempt['year'] ?? null,
                        'narration_id' => $attempt['narration_id'] ?? null,
                        'drawing_id' => $attempt['drawing_id'] ?? null,
                        'side' => $attempt['side'] ?? null,
                        'result' => $attempt['result'] ?? null,
                        'percentage' => $attempt['percentage'] ?? null,
                    ]);
                }
            }
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
        
        $examinee->delete();
        
        return redirect()->route('examinees.index')
            ->with('success', 'تم حذف الممتحن بنجاح');
    }

    public function print(Request $request)
    {
        $user = auth()->user();
        $userClusterIds = $user->clusters()->pluck('clusters.id')->toArray();
        
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);
    
        // Apply cluster filter based on user
        if (!empty($userClusterIds)) {
            $query->whereIn('cluster_id', $userClusterIds);
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
    
        if ($request->filled('cluster_id')) {
            $clusterIds = (array)$request->cluster_id;
            if (!empty($userClusterIds)) {
                $clusterIds = array_intersect($clusterIds, $userClusterIds);
            }
            if (!empty($clusterIds)) {
                $query->whereIn('cluster_id', $clusterIds);
            }
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
    
        return view('examinees.print', compact('examinees'));
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
    
        return redirect()->route('examinees.index')
            ->with('success', 'تم استيراد الممتحنين وجميع محاولاتهم بنجاح');
    }


    public function printCards(Request $request)
    {
        $ids = explode(',', $request->ids);
        
        $examinees = Examinee::with(['cluster', 'narration', 'drawing'])
            ->whereIn('id', $ids)
            ->get();
        
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
    
        return redirect()->route('examinees.index')
            ->with('success', 'تم رفض الممتحن بنجاح');
    }
}