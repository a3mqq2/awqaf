<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NarrationController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShippingTypeController;
use App\Http\Controllers\DeliveryPriceController;
use App\Http\Controllers\ExamineeCheckController;
use App\Http\Controllers\InvoiceExpenseController;
use App\Http\Controllers\InvoicePaymentController;
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



    Route::prefix('system-logs')->name('system_logs.')->middleware('auth')->group(function () {
        Route::get('/', [SystemLogController::class, 'index'])->name('index');
        Route::get('/{systemLog}', [SystemLogController::class, 'show'])->name('show');
        Route::delete('/{systemLog}', [SystemLogController::class, 'destroy'])->name('destroy');
        Route::delete('/clear/all', [SystemLogController::class, 'clear'])->name('clear');
    });

    
    // contact.send
    Route::get('/backup/download', [BackupController::class, 'download'])
    ->name('backup.download');
    // Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); 
});


Route::post('contact/send', [DashboardController::class, 'send'])->name('contact.send');
