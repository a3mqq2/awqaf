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
            'birth_date' => 'required|date',
            'office_id' => 'required|exists:offices,id',
            'cluster_id' => 'required|exists:clusters,id',
            'narration_id' => 'required|exists:narrations,id',
            'drawing_id' => 'required|exists:drawings,id',
        ];

        if ($request->identity_type === 'national_id') {
            $rules['national_id'] = 'required|string|max:50|unique:examinees,national_id';
        } else {
            $rules['passport_no'] = 'required|string|max:50|unique:examinees,passport_no';
        }

        $data = $request->validate($rules);
        $data['phone'] = '218' . $data['phone'];
        
        // Generate full_name
        $data['full_name'] = trim(
            ($data['first_name'] ?? '') . ' ' . 
            ($data['father_name'] ?? '') . ' ' . 
            ($data['grandfather_name'] ?? '') . ' ' . 
            ($data['last_name'] ?? '')
        );

        // Set status as confirmed
        $data['status'] = 'under_review';

        // Remove identity_type from data
        unset($data['identity_type']);

        $examinee = Examinee::create($data);

        return redirect()->route('public.registration.success', $examinee->id);
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
        $request->validate([
            'confirmation' => 'required|string|in:انا اوكد الانسحاب',
        ]);

        $examinee->update(['status' => 'withdrawn']);

        return redirect()->route('public.registration.index')
            ->with('success', 'تم الانسحاب من التسجيل');
    }
}