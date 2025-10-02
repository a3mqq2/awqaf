<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShippingTypeController;
use App\Http\Controllers\DeliveryPriceController;
use App\Http\Controllers\ExamineeCheckController;
use App\Http\Controllers\InvoiceExpenseController;
use App\Http\Controllers\InvoicePaymentController;

Route::redirect('/', '/dashboard');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'submit'])->name('login.submit');

Route::get('/examinee/check', [ExamineeCheckController::class, 'showForm'])->name('examinee.check.form');
Route::post('/examinee/check', [ExamineeCheckController::class, 'check'])->name('examinee.check');
Route::post('/examinee/confirm/{examinee}', [ExamineeCheckController::class, 'confirm'])->name('examinee.confirm');
Route::post('/examinee/withdraw/{examinee}', [ExamineeCheckController::class, 'withdraw'])->name('examinee.withdraw');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('clusters', ClusterController::class);
    Route::patch('clusters/{cluster}/toggle', [ClusterController::class, 'toggle'])->name('clusters.toggle');

    Route::prefix('users')->name('users.')->group(function () {
        Route::patch('{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle');
    });
    Route::resource('users', UserController::class);

    Route::resource('offices', OfficeController::class);
    Route::patch('offices/{office}/toggle', [OfficeController::class, 'toggle'])->name('offices.toggle');

    Route::resource('narrations', NarrationController::class);
    Route::patch('narrations/{narration}/toggle', [NarrationController::class, 'toggle'])->name('narrations.toggle');

    Route::resource('drawings', DrawingController::class);
    Route::patch('drawings/{drawing}/toggle', [DrawingController::class, 'toggle'])->name('drawings.toggle');


    Route::get('examinees/print', [ExamineeController::class, 'print'])->name('examinees.print');
    Route::get('examinees/import-form', [ExamineeController::class, 'importForm'])->name('examinees.import.form');
    Route::post('examinees/import', [ExamineeController::class, 'import'])->name('examinees.import');
    Route::resource('examinees', ExamineeController::class);



    Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); 
});
