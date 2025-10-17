<?php

namespace App\Http\Controllers;

use App\Models\Examinee;
use Illuminate\Http\Request;

class ExamineeCheckController extends Controller
{
    /**
     * Show the check form
     */
    public function showForm()
    {
        return view('examinee.check');
    }

    /**
     * Check examinee data
     */
    public function check(Request $request)
    {
        $request->validate([
            'national_id' => 'required|string',
            'passport_no' => 'required|string',
        ], [
            'national_id.required' => 'الرقم الوطني مطلوب',
            'passport_no.required' => 'رقم الجواز مطلوب',
        ]);

        // Find examinee with all three matching fields
        $examinee = Examinee::where('national_id', $request->national_id)
            ->where('passport_no', $request->passport_no)
            ->with(['office', 'cluster', 'examAttempts.narration', 'examAttempts.drawing'])
            ->first();

        if (!$examinee) {
            return back()->with('error', 'لم يتم العثور على بياناتك. يرجى التأكد من صحة المعلومات المدخلة.');
        }

        return view('examinee.details', compact('examinee'));
    }

    /**
     * Confirm participation
     */
    public function confirm(Request $request, Examinee $examinee)
    {
        // Verify the examinee data matches
        $request->validate([
            'national_id' => 'required|string',
            'passport_no' => 'required|string',
        ]);

        // Security check: make sure the submitted data matches the examinee
        if ($examinee->national_id != $request->national_id || 
            $examinee->passport_no != $request->passport_no) {
            return redirect()->route('examinee.check.form')
                ->with('error', 'بيانات غير صحيحة');
        }

        $examinee->update(['status' => 'confirmed']);

        return redirect()->route('examinee.check.form')
            ->with('success', 'تم تأكيد مشاركتك بنجاح! شكراً لك.');
    }

    /**
     * Withdraw from exam
     */
    public function withdraw(Request $request, Examinee $examinee)
    {
        // Verify the examinee data matches
        $request->validate([
            'national_id' => 'required|string',
            'passport_no' => 'required|string',
        ]);

        // Security check: make sure the submitted data matches the examinee
        if ($examinee->national_id != $request->national_id || 
            $examinee->passport_no != $request->passport_no) {
            return redirect()->route('examinee.check.form')
                ->with('error', 'بيانات غير صحيحة');
        }

        $examinee->update(['status' => 'withdrawn']);

        return redirect()->route('examinee.check.form')
            ->with('success', 'تم تسجيل انسحابك. نأسف لعدم تمكنك من المشاركة.');
    }
}