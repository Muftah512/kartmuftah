<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PointOfSaleController as AdminPointOfSaleController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboard;
use App\Http\Controllers\Accountant\InvoiceController as AccountantInvoiceController;
use App\Http\Controllers\Accountant\RechargeController as AccountantRechargeController;
use App\Http\Controllers\Accountant\PointOfSaleController as AccountantPointOfSaleController; // تم التصحيح هنا
use App\Http\Controllers\Pos\DashboardController as PosDashboard;
use App\Http\Controllers\Pos\CardController as PosCardController;
use App\Http\Controllers\Pos\SaleController as PosSaleController;
use App\Http\Controllers\Accountant\PosReportController;
use App\Http\Controllers\Accountant\TransactionsReportController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout',[LoginController::class, 'logout'])->name('logout');
/*
|--------------------------------------------------------------------------
| Home Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth','role:admin','ensure.active'])
    ->group(function(){

        // Dashboard
        Route::get('dashboard', [AdminDashboard::class, 'index'])
             ->name('dashboard');

        // Users CRUD
        Route::resource('users', AdminUserController::class);

        // Point-of-Sale CRUD
        Route::resource('pos', AdminPointOfSaleController::class);

        // Packages CRUD
        Route::resource('packages', AdminPackageController::class);

        // Reports
        Route::prefix('reports')
            ->name('reports.')
            ->group(function(){

                // 1) تقرير المبيعات
                Route::get('sales', [AdminReportsController::class,'salesReport'])
                     ->name('sales');
                Route::get('export/sales', [AdminReportsController::class,'exportSalesReport'])
                     ->name('export.sales');
                Route::get('export/pdf/sales',[AdminReportsController::class,'pdfSalesReport'])
                     ->name('export.pdf.sales');

                // 2) تقرير بطاقات الإنترنت
                Route::get('cards', [AdminReportsController::class,'cardsReport'])
                     ->name('cards');
                Route::get('export/cards', [AdminReportsController::class,'exportCardsReport'])
                     ->name('export.cards');

                // 3) التقرير المالي
                Route::get('financial', [AdminReportsController::class,'financialReport'])
                     ->name('financial');
                Route::get('export/financial',[AdminReportsController::class,'exportFinancialReport'])
                     ->name('export.financial');

                // 4) تقرير المستخدمين
                Route::get('users', [AdminReportsController::class,'usersReport'])
                     ->name('users');
            });
    });
 
/*
|--------------------------------------------------------------------------
| Accountant Routes
|--------------------------------------------------------------------------
*/
Route::prefix('accountant')
    ->name('accountant.')
    ->middleware(['auth','role:accountant','ensure.active'])
    ->group(function(){
        // Dashboard
        Route::get('/', [AccountantDashboard::class, 'index'])->name('dashboard');
        
        // POS Management
        Route::prefix('pos')
            ->name('pos.')
            ->group(function(){
                Route::get('/', [AccountantPointOfSaleController::class, 'index'])->name('index');
                Route::get('/create', [AccountantPointOfSaleController::class, 'create'])->name('create');
                Route::post('/', [AccountantPointOfSaleController::class, 'store'])->name('store');
                Route::get('/{id}', [AccountantPointOfSaleController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [AccountantPointOfSaleController::class, 'edit'])->name('edit');
                Route::patch('/{id}', [AccountantPointOfSaleController::class, 'update'])->name('update');
                Route::delete('/{id}', [AccountantPointOfSaleController::class, 'destroy'])->name('destroy');
                // إعادة تعيين كلمة المرور
                Route::post('/{id}/reset-password', [AccountantPointOfSaleController::class, 'resetPassword'])
                    ->name('reset-password');
                
                // تعطيل/تفعيل النقطة
                Route::patch('/{id}/toggle-status', [AccountantPointOfSaleController::class, 'toggleStatus'])
                    ->name('toggle-status');
            });
        
        // Invoices

        Route::resource('invoices', AccountantInvoiceController::class);
        
        // Recharges
        Route::resource('recharges', AccountantRechargeController::class)
            ->only(['index','create','store']);
        
        // Reports
        Route::get('reports/pos', [PosReportController::class, 'index'])
            ->name('reports.pos');
        Route::get('reports/transactions', [TransactionsReportController::class, 'index'])
            ->name('reports.transactions');
    });
/*
|--------------------------------------------------------------------------
| Point-of-Sale (POS) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pos')
    ->name('pos.')
    ->middleware(['auth','role:pos','ensure.active'])
    ->group(function(){
        Route::get('dashboard', [PosDashboard::class,'index'])->name('dashboard');

        // إدارة البطاقات
        Route::prefix('cards')
            ->name('cards.')
            ->group(function(){
                Route::get('generate',  [PosCardController::class,'generateForm'])->name('generate');
                Route::post('generate', [PosCardController::class,'generate'])->name('generate.submit');
                Route::get('recharge',  [PosCardController::class,'rechargeForm'])->name('recharge');
                Route::post('recharge', [PosCardController::class,'recharge'])->name('recharge.submit');
                Route::get('result/{card}', [PosCardController::class,'result'])->name('result');
            });

        // المبيعات
        Route::get('sales', [PosSaleController::class,'index'])->name('sales.index');
    });

/*
|--------------------------------------------------------------------------
| Jetstream / Sanctum (optional)
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function(){
    Route::get('/dashboard', fn() => view('dashboard'))
         ->name('dashboard');
});
