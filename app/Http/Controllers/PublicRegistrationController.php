<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use App\Models\Office;
use App\Models\Cluster;
use App\Models\Narration;
use App\Models\Drawing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicRegistrationController extends Controller
{
    /**
     * Show main registration page
     */
    public function index()
    {
        return view('public.registration.index');
    }

    /**
     * Show check form
     */
    public function checkForm()
    {
        return view('public.registration.check');
    }

    /**
     * Check existing registration
     */
    public function checkRegistration(Request $request)
    {
        $request->validate([
            'identity_type' => 'required|in:national_id,passport',
            'identity_number' => 'required|string',
        ]);

        $query = Examinee::query();

        if ($request->identity_type === 'national_id') {
            $query->where('national_id', $request->identity_number);
        } else {
            $query->where('passport_no', $request->identity_number);
        }

        $examinee = $query->first();

        if (!$examinee) {
            return redirect()->back()
                ->with('error', 'لم يتم العثور على بياناتك. يرجى التسجيل كممتحن جديد.')
                ->with('show_register', true);
        }

        return view('public.registration.details', compact('examinee'));
    }

    /**
     * Show new registration form
     */
    public function registerForm()
    {
        $offices = Office::where('is_active', true)->get();
        $clusters = Cluster::where('is_active', true)->get();
        $narrations = Narration::where('is_active', true)->get();
        $drawings = Drawing::where('is_active', true)->get();
        
        return view('public.registration.register', compact('offices', 'clusters', 'narrations', 'drawings'));
    }

    /**
     * Store new registration
     */
    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'grandfather_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'identity_type' => 'required|in:national_id,passport',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'current_residence' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date|before_or_equal:2009-12-31',
            'cluster_id' => 'required|exists:clusters,id',
            'office_name' => 'required|string|max:255',
            'narration_name' => 'required|string|max:255',
            'drawing_name' => 'required|string|max:255',
        ];
    
        if ($request->identity_type === 'national_id') {
            $rules['national_id'] = 'required|string|size:12';
        } else {
            $rules['passport_no'] = 'required|string|max:50';
        }
    
        $data = $request->validate($rules);
        
        // Prepare phone number with country code
        $phoneWithCode = '218' . $data['phone'];
        
        // Check if user is already registered - IMPROVED
        $existingExaminee = null;
        
        if ($request->identity_type === 'national_id') {
            $existingExaminee = Examinee::where('national_id', $data['national_id'])->first();
            
            if ($existingExaminee) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'الرقم الوطني مسجل مسبقاً في امتحان الإجازة. يمكنك مراجعة تسجيلك من خلال صفحة الاستعلام.');
            }
        } else {
            // التحقق من رقم جواز السفر
            $existingExaminee = Examinee::where('passport_no', $data['passport_no'])->first();
            
            if ($existingExaminee) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'رقم جواز السفر مسجل مسبقاً في امتحان الإجازة. يمكنك مراجعة تسجيلك من خلال صفحة الاستعلام.');
            }
        }
        
        // Add +218 to phone numbers
        $data['phone'] = $phoneWithCode;
        if (!empty($data['whatsapp'])) {
            $data['whatsapp'] = '218' . $data['whatsapp'];
        }
        
        // Generate full_name
        $data['full_name'] = trim(
            ($data['first_name'] ?? '') . ' ' . 
            ($data['father_name'] ?? '') . ' ' . 
            ($data['grandfather_name'] ?? '') . ' ' . 
            ($data['last_name'] ?? '')
        );
    
        // Handle Office with firstOrCreate
        $office = Office::firstOrCreate(
            ['name' => $data['office_name']],
            ['name' => $data['office_name']]
        );
        $data['office_id'] = $office->id;
        unset($data['office_name']);
    
        // Handle Narration with firstOrCreate
        $narration = Narration::firstOrCreate(
            ['name' => $data['narration_name']],
            ['name' => $data['narration_name']]
        );
        $data['narration_id'] = $narration->id;
        unset($data['narration_name']);
    
        // Handle Drawing with firstOrCreate
        $drawing = Drawing::firstOrCreate(
            ['name' => $data['drawing_name']],
            ['name' => $data['drawing_name']]
        );
        $data['drawing_id'] = $drawing->id;
        unset($data['drawing_name']);
    
        // Set status as under review
        $data['status'] = 'under_review';
    
        // Remove identity_type from data
        unset($data['identity_type']);
    
        try {
            $examinee = Examinee::create($data);
            return redirect()->route('public.registration.success', $examinee->id);
        } catch (\Exception $e) {
            // في حالة حدوث أي خطأ في قاعدة البيانات
            \Log::error('Registration error: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء التسجيل. قد تكون البيانات مسجلة مسبقاً. يرجى المحاولة مرة أخرى أو التواصل مع الدعم الفني.');
        }
    }
    /**
     * Show success page
     */
    public function success($id)
    {
        $examinee = Examinee::findOrFail($id);
        
        return view('public.registration.success', compact('examinee'));
    }

    /**
     * Confirm registration
     */
    public function confirm(Examinee $examinee)
    {
        $examinee->update(['status' => 'confirmed']);

        return redirect()->route('public.registration.success', $examinee->id)
            ->with('success', 'تم تأكيد التسجيل بنجاح');
    }

    /**
     * Withdraw registration
     */
    public function withdraw(Request $request, Examinee $examinee)
    {
        $examinee->update(['status' => 'withdrawn']);

        return redirect()->route('public.registration.index')
            ->with('success', 'تم الانسحاب من التسجيل');
    }


// الكود الموجود حاليًا...
    
    /**
     * البحث عن المكاتب - AJAX
     */
    public function searchOffices(Request $request)
    {
        $query = $request->get('query', '');
        
        $offices = Office::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'name']);
        
        return response()->json($offices);
    }
    
    /**
     * البحث عن الروايات - AJAX
     */
    public function searchNarrations(Request $request)
    {
        $query = $request->get('query', '');
        
        $narrations = Narration::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'name']);
        
        return response()->json($narrations);
    }
    
    /**
     * البحث عن الرسوم - AJAX
     */
    public function searchDrawings(Request $request)
    {
        $query = $request->get('query', '');
        
        $drawings = Drawing::where('name', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->limit(10)
            ->get(['id', 'name']);
        
        return response()->json($drawings);
    }
}