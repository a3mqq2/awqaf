<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExamineeController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NarrationController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NarrationPdfController;
use App\Http\Controllers\ShippingTypeController;
use App\Http\Controllers\DeliveryPriceController;
use App\Http\Controllers\ExamineeCheckController;
use App\Http\Controllers\ExamineeReportController;
use App\Http\Controllers\InvoiceExpenseController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\JudgeDashboardController;
use App\Http\Controllers\OralEvaluationController;
use App\Http\Controllers\PublicRegistrationController;

Route::redirect('/', '/registration');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'submit'])->name('login.submit');

// Public Registration Routes
Route::prefix('registration')->name('public.registration.')->group(function () {
    // Main Pages
    Route::get('/', [PublicRegistrationController::class, 'index'])->name('index');
    Route::get('/check', [PublicRegistrationController::class, 'checkForm'])->name('check.form');
    Route::post('/check', [PublicRegistrationController::class, 'checkRegistration'])->name('check');
    Route::get('/register', [PublicRegistrationController::class, 'registerForm'])->name('register.form');
    Route::post('/register', [PublicRegistrationController::class, 'store'])->name('register');
    Route::get('/success/{id}', [PublicRegistrationController::class, 'success'])->name('success');
    Route::post('/confirm/{examinee}', [PublicRegistrationController::class, 'confirm'])->name('confirm');
    Route::post('/withdraw/{examinee}', [PublicRegistrationController::class, 'withdraw'])->name('withdraw');
    Route::get('/print-card', [ExamineeController::class, 'printCards'])->name('print-card');
    
    // AJAX Search Routes
    Route::get('/search/offices', [PublicRegistrationController::class, 'searchOffices'])->name('search.offices');
    Route::get('/search/narrations', [PublicRegistrationController::class, 'searchNarrations'])->name('search.narrations');
    Route::get('/search/drawings', [PublicRegistrationController::class, 'searchDrawings'])->name('search.drawings');
});


// Oral Evaluation Routes (الاختبار الشفهي)
Route::middleware(['auth'])->prefix('judge/oral')->name('judge.oral.')->group(function () {
    Route::get('/dashboard', [OralEvaluationController::class, 'index'])->name('dashboard');
    Route::post('/receive-examinee', [OralEvaluationController::class, 'receiveExaminee'])->name('receive');
    Route::get('/evaluate/{evaluation}', [OralEvaluationController::class, 'evaluate'])->name('evaluate');
    Route::post('/evaluate/{evaluation}/save', [OralEvaluationController::class, 'save'])->name('save');
    Route::post('/evaluate/{evaluation}/exclude', [OralEvaluationController::class, 'exclude'])->name('exclude');
    Route::get('/completed', [OralEvaluationController::class, 'completed'])->name('completed');
});



// ======= Judge Routes (Written Test - المنهج العلمي) =======
Route::middleware(['auth', 'role:judge'])->prefix('judge')->name('judge.')->group(function () {
    Route::get('/dashboard', [JudgeDashboardController::class, 'index'])->name('dashboard');
    Route::post('/receive-examinee', [JudgeDashboardController::class, 'receiveExaminee'])->name('receive');
    Route::get('/evaluate/{evaluation}', [JudgeDashboardController::class, 'startEvaluation'])->name('evaluate');
    Route::post('/evaluate/{evaluation}/save', [JudgeDashboardController::class, 'saveEvaluation'])->name('evaluate.save');
    Route::get('/completed', [JudgeDashboardController::class, 'completedEvaluations'])->name('completed');
});



Route::middleware(['auth'])->group(function () {
    Route::get('examinees/print-options', [ExamineeController::class, 'printOptions'])->name('examinees.print.options');
    Route::get('examinees/export-pdf', [ExamineeController::class, 'exportPdf'])->name('examinees.export.pdf');
    Route::get('examinees/export-excel', [ExamineeController::class, 'exportExcel'])->name('examinees.export.excel');
});


// ======= Oral Evaluation Routes (الاختبار الشفهي) =======
Route::middleware(['auth', 'role:judge'])->prefix('judge/oral')->name('judge.oral.')->group(function () {
    // Dashboard
    Route::get('/', [OralEvaluationController::class, 'index'])->name('dashboard');
    
    // استقبال الممتحن
    Route::post('/receive', [OralEvaluationController::class, 'receiveExaminee'])->name('receive');
    
    // صفحة التقييم
    Route::get('/evaluate/{evaluation}', [OralEvaluationController::class, 'evaluate'])->name('evaluate');
    
    // تحديث البنود أثناء التقييم
    Route::post('/evaluate/{evaluation}/update-deduction', [OralEvaluationController::class, 'updateDeduction'])->name('update.deduction');
    
    // اعتماد السؤال الحالي
    Route::post('/evaluate/{evaluation}/approve-question', [OralEvaluationController::class, 'approveQuestion'])->name('approve.question');
    
    // الرجوع للسؤال السابق
    Route::post('/evaluate/{evaluation}/previous-question', [OralEvaluationController::class, 'previousQuestion'])->name('previous.question');
    
    // حفظ التقييم النهائي
    Route::post('/evaluate/{evaluation}/save', [OralEvaluationController::class, 'save'])->name('save');
    
    // استبعاد الممتحن
    Route::post('/evaluate/{evaluation}/exclude', [OralEvaluationController::class, 'exclude'])->name('exclude');
    
    // التقييمات المكتملة
    Route::get('/completed', [OralEvaluationController::class, 'completed'])->name('completed');
});
// Narration PDFs Management
Route::middleware(['auth', 'can:narrations'])->prefix('narrations/{narration}/pdfs')->name('narrations.pdfs.')->group(function () {
    Route::get('/', [NarrationPdfController::class, 'index'])->name('index');
    Route::post('/', [NarrationPdfController::class, 'store'])->name('store');
    Route::post('/update-order', [NarrationPdfController::class, 'updateOrder'])->name('update-order');
    Route::patch('/{pdf}/toggle', [NarrationPdfController::class, 'toggle'])->name('toggle');
    Route::delete('/{pdf}', [NarrationPdfController::class, 'destroy'])->name('destroy');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Clusters
    Route::resource('clusters', ClusterController::class);
    Route::patch('clusters/{cluster}/toggle', [ClusterController::class, 'toggle'])->name('clusters.toggle');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::patch('{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle');
    });
    Route::resource('users', UserController::class);

    // Offices
    Route::resource('offices', OfficeController::class);
    Route::patch('offices/{office}/toggle', [OfficeController::class, 'toggle'])->name('offices.toggle');

    // Narrations
    Route::resource('narrations', NarrationController::class);
    Route::patch('narrations/{narration}/toggle', [NarrationController::class, 'toggle'])->name('narrations.toggle');

    // Drawings
    Route::resource('drawings', DrawingController::class);
    Route::patch('drawings/{drawing}/toggle', [DrawingController::class, 'toggle'])->name('drawings.toggle');

    // Examinees
    Route::get('examinees/print', [ExamineeController::class, 'print'])->name('examinees.print');
    Route::get('examinees/import-form', [ExamineeController::class, 'importForm'])->name('examinees.import.form');
    Route::get('examinees/print-cards', [ExamineeController::class, 'printCards'])->name('examinees.print.cards');
    Route::post('examinees/import', [ExamineeController::class, 'import'])->name('examinees.import');
    Route::patch('examinees/{examinee}/approve', [ExamineeController::class, 'approve'])->name('examinees.approve');
    Route::patch('examinees/{examinee}/reject', [ExamineeController::class, 'reject'])->name('examinees.reject');
    Route::resource('examinees', ExamineeController::class);

    // Committees Management (اللجان)
    Route::middleware('can:committees.view')->group(function () {
        Route::resource('committees', CommitteeController::class);
    });

    // Judges Management (المحكمين)
    Route::middleware('can:judges.view')->group(function () {
        Route::resource('judges', JudgeController::class);
        Route::patch('judges/{judge}/toggle-status', [JudgeController::class, 'toggleStatus'])->name('judges.toggle-status');
    });


    // Examinee Reports (للمدير)
    Route::middleware(['auth'])->group(function () {
        Route::get('/reports/examinees', [ExamineeReportController::class, 'index'])->name('reports.examinees');
        Route::get('/reports/examinees/{examinee}/receipt', [ExamineeReportController::class, 'printReceipt'])->name('reports.receipt');
    });


    // Attendance (تسجيل الحضور) - خاص بكنترول اللجنة
    Route::middleware('can:attendance.mark')->prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/search', [AttendanceController::class, 'search'])->name('search');
        Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
        Route::post('/cancel', [AttendanceController::class, 'cancelAttendance'])->name('cancel');
        Route::get('/report', [AttendanceController::class, 'report'])->name('report')->middleware('can:attendance.view');
        Route::get('/print', [AttendanceController::class, 'printReport'])->name('print')->middleware('can:attendance.view');
    });

    // System Logs
    Route::prefix('system-logs')->name('system_logs.')->group(function () {
        Route::get('/', [SystemLogController::class, 'index'])->name('index');
        Route::get('/{systemLog}', [SystemLogController::class, 'show'])->name('show');
        Route::delete('/{systemLog}', [SystemLogController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/all', [SystemLogController::class, 'clear'])->name('clear');
    });

    // Backup
    Route::get('/backup/download', [BackupController::class, 'download'])->name('backup.download');
    
    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // API Routes
    // API للحصول على اللجان حسب التجمع
    Route::get('/api/committees-by-cluster/{cluster}', function($clusterId) {
        $user = Auth::user();
        
        $query = App\Models\Committee::where('cluster_id', $clusterId);
        
        // إذا كان المستخدم ليس أدمن، يعرض فقط اللجان في تجمعاته
        if ($user->hasRole('committee_control') || $user->hasRole('committee_supervisor')) {
            if (!$user->clusters->pluck('id')->contains($clusterId)) {
                return response()->json([], 403);
            }
        }
        
        $committees = $query->select('id', 'name')->get();
        
        return response()->json($committees);
    });
});

// Contact Form (Public)
Route::post('contact/send', [DashboardController::class, 'send'])->name('contact.send');




Route::get('/cache-pdfs', function () {
    $files = [
        'q' => 'https://testing.waqsa.ly/storage/q.pdf',
        'msqam' => 'https://testing.waqsa.ly/storage/msqam.pdf',
    ];

    $results = [];

    foreach ($files as $key => $url) {
        $path = storage_path("app/public/{$key}.pdf");

        if (file_exists($path) && filesize($path) > 0) {
            $results[$key] = 'already exists ✅';
            continue;
        }

        $readStream = @fopen($url, 'rb');
        if (!$readStream) {
            $results[$key] = 'failed to open remote file ❌';
            continue;
        }

        $writeStream = @fopen($path, 'wb');
        if (!$writeStream) {
            fclose($readStream);
            $results[$key] = 'failed to write local file ❌';
            continue;
        }

        stream_copy_to_stream($readStream, $writeStream);
        fclose($readStream);
        fclose($writeStream);

        $results[$key] = file_exists($path) ? 'downloaded ✅' : 'failed ❌';
    }

    return response()->json($results);
});


Route::get('/pdf/{key}', function ($key) {
    $filePath = Cache::get($key);
    if (!$filePath || !file_exists($filePath)) {
        return response('File not found', 404);
    }
    return response()->file($filePath);
});