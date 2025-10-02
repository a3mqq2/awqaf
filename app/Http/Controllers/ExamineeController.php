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
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);

        // Search by name
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->name.'%')
                  ->orWhere('father_name', 'like', '%'.$request->name.'%')
                  ->orWhere('last_name', 'like', '%'.$request->name.'%')
                  ->orWhere('grandfather_name', 'like', '%'.$request->name.'%');
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

        // Filter by office
        if ($request->filled('office_id')) {
            $query->where('office_id', $request->office_id);
        }

        // Filter by cluster
        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        // Filter by narration
        if ($request->filled('narration_id')) {
            $query->where('narration_id', $request->narration_id);
        }

        // Filter by drawing
        if ($request->filled('drawing_id')) {
            $query->where('drawing_id', $request->drawing_id);
        }

        // Filter by current residence
        if ($request->filled('current_residence')) {
            $query->where('current_residence', 'like', '%'.$request->current_residence.'%');
        }

        $examinees = $query->latest()->paginate(15);
        
        // Load data for filters
        $offices = Office::where('is_active', true)->get();
        $clusters = Cluster::where('is_active', true)->get();
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();

        return view('examinees.index', compact('examinees', 'offices', 'clusters', 'narrations', 'drawings'));
    }

    public function create()
    {
        $offices = Office::all();
        $clusters = Cluster::all();
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
        
        return view('examinees.create', compact('offices', 'clusters', 'narrations', 'drawings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50|unique:examinees,national_id',
            'passport_no' => 'nullable|string|max:50|unique:examinees,passport_no',
            'phone' => 'nullable|string|max:20',
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
        $examinee->load(['office', 'cluster', 'narration', 'drawing', 'examAttempts.narration', 'examAttempts.drawing']);
        
        return view('examinees.show', compact('examinee'));
    }

    public function edit(Examinee $examinee)
    {
        $offices = Office::all();
        $clusters = Cluster::all();
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
        $examinee->load('examAttempts');
        
        return view('examinees.edit', compact('examinee', 'offices', 'clusters', 'narrations', 'drawings'));
    }

    public function update(Request $request, Examinee $examinee)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50|unique:examinees,national_id,'.$examinee->id,
            'passport_no' => 'nullable|string|max:50|unique:examinees,passport_no,'.$examinee->id,
            'phone' => 'nullable|string|max:20',
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

        $examinee->update($data);

        // Handle exam attempts - delete all old ones and recreate
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
        $examinee->delete();
        
        return redirect()->route('examinees.index')
            ->with('success', 'تم حذف الممتحن بنجاح');
    }

    public function print(Request $request)
    {
        $query = Examinee::with(['office', 'cluster', 'narration', 'drawing']);

        // Apply same filters as index
        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%'.$request->name.'%')
                  ->orWhere('father_name', 'like', '%'.$request->name.'%')
                  ->orWhere('last_name', 'like', '%'.$request->name.'%')
                  ->orWhere('grandfather_name', 'like', '%'.$request->name.'%');
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
            $query->where('office_id', $request->office_id);
        }

        if ($request->filled('cluster_id')) {
            $query->where('cluster_id', $request->cluster_id);
        }

        if ($request->filled('narration_id')) {
            $query->where('narration_id', $request->narration_id);
        }

        if ($request->filled('drawing_id')) {
            $query->where('drawing_id', $request->drawing_id);
        }

        if ($request->filled('current_residence')) {
            $query->where('current_residence', 'like', '%'.$request->current_residence.'%');
        }

        $examinees = $query->latest()->get();

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
}