<?php

namespace App\Http\Controllers;

use App\Models\Narration;
use App\Models\NarrationPdf;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NarrationPdfController extends Controller
{
    /**
     * عرض قائمة PDFs للرواية
     */
    public function index(Narration $narration)
    {
        $pdfs = $narration->pdfs()->orderBy('order')->get();
        
        return view('narrations.pdfs.index', compact('narration', 'pdfs'));
    }

    /**
     * رفع PDF جديد
     */
    public function store(Request $request, Narration $narration)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'required|file|mimes:pdf|max:51200', // 50MB max
            'order' => 'nullable|integer|min:0',
        ], [
            'pdf_file.required' => 'يجب اختيار ملف PDF',
            'pdf_file.mimes' => 'يجب أن يكون الملف بصيغة PDF',
            'pdf_file.max' => 'حجم الملف يجب ألا يتجاوز 50 ميجابايت',
        ]);

        // رفع الملف
        $file = $request->file('pdf_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('pdfs/narrations/' . $narration->id, $filename, 'public');

        // حفظ في قاعدة البيانات
        $pdf = NarrationPdf::create([
            'narration_id' => $narration->id,
            'title' => $request->title,
            'file_path' => $path,
            'order' => $request->order ?? 0,
            'is_active' => true,
        ]);

        // Log
        SystemLog::create([
            'description' => "تم رفع ملف PDF: {$request->title} للرواية: {$narration->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('narrations.pdfs.index', $narration)
                        ->with('success', 'تم رفع الملف بنجاح');
    }

    /**
     * تحديث ترتيب PDFs
     */
    public function updateOrder(Request $request, Narration $narration)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $pdfId => $order) {
            NarrationPdf::where('id', $pdfId)
                        ->where('narration_id', $narration->id)
                        ->update(['order' => $order]);
        }

        // Log
        SystemLog::create([
            'description' => "تم تحديث ترتيب ملفات PDF للرواية: {$narration->name}",
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }

    /**
     * تفعيل/إلغاء تفعيل PDF
     */
    public function toggle(Narration $narration, NarrationPdf $pdf)
    {
        // التحقق من أن الـ PDF تابع للرواية
        if ($pdf->narration_id !== $narration->id) {
            abort(404);
        }

        $pdf->is_active = !$pdf->is_active;
        $pdf->save();

        $status = $pdf->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        // Log
        SystemLog::create([
            'description' => "تم {$status} ملف PDF: {$pdf->title} للرواية: {$narration->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('narrations.pdfs.index', $narration)
                        ->with('success', 'تم ' . $status . ' الملف بنجاح');
    }

    /**
     * حذف PDF
     */
    public function destroy(Narration $narration, NarrationPdf $pdf)
    {
        // التحقق من أن الـ PDF تابع للرواية
        if ($pdf->narration_id !== $narration->id) {
            abort(404);
        }

        $title = $pdf->title;

        // حذف الملف من التخزين
        if (Storage::disk('public')->exists($pdf->file_path)) {
            Storage::disk('public')->delete($pdf->file_path);
        }

        // حذف من قاعدة البيانات
        $pdf->delete();

        // Log
        SystemLog::create([
            'description' => "تم حذف ملف PDF: {$title} من الرواية: {$narration->name}",
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('narrations.pdfs.index', $narration)
                        ->with('success', 'تم حذف الملف بنجاح');
    }
}