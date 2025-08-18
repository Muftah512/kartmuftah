<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PointOfSaleController as AdminPointOfSaleController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\AccountantTopupReportController;
use App\Http\Controllers\Accountant\DashboardController as AccountantDashboard;
use App\Http\Controllers\Accountant\InvoiceController as AccountantInvoiceController;
use App\Http\Controllers\Accountant\RechargeController as AccountantRechargeController;
use App\Http\Controllers\Accountant\PointOfSaleController as AccountantPointOfSaleController; // تم التصحيح هنا
use App\Http\Controllers\Pos\DashboardController;
use App\Http\Controllers\Pos\InternetCardController;
use App\Http\Controllers\Pos\SaleController as PosSaleController;
use App\Http\Controllers\Pos\ProfileController;
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
         Route::resource('users', UserController::class);

        // تبديل حالة التفعيل
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Point-of-Sale CRUD
        Route::resource('pos', AdminPointOfSaleController::class);

        Route::get('accountants/topups', [AccountantTopupReportController::class, 'index'])->name('accountants.topups.index');
        Route::get('accountants/topups/export', [AccountantTopupReportController::class, 'export'])->name('accountants.topups.export');

        // Packages CRUD
        Route::resource('packages', AdminPackageController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::get('/profile/avatar/show', [ProfileController::class, 'showAvatar'])->name('profile.avatar.show');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

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

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::get('/profile/avatar/show', [ProfileController::class, 'showAvatar'])->name('profile.avatar.show');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    });
/*
|--------------------------------------------------------------------------
| Point-of-Sale (POS) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('pos')
    ->name('pos.')
    ->middleware(['auth', 'role:pos', 'ensure.active'])
    ->group(function() {
        // لوحة التحكم
        Route::get('pos/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::get('/profile/avatar/show', [ProfileController::class, 'showAvatar'])->name('profile.avatar.show');
        Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

        
        // إدارة البطاقات
        Route::prefix('cards')
            ->name('cards.')
            ->group(function() {
                Route::get('generate', [InternetCardController::class, 'generateForm'])->name('generate');
                Route::post('generate', [InternetCardController::class, 'generate'])->name('generate.submit');
                
                Route::get('recharge', [InternetCardController::class, 'rechargeForm'])->name('recharge');
                Route::post('/recharge', [InternetCardController::class, 'recharge'])->name('recharge.submit');
                Route::get('/cards', [InternetCardController::class, 'index']);
                Route::get('result/{Internetcard}', [InternetCardController::class, 'result'])
                    ->name('result')
                    ->where('card', '[0-9]+'); // التأكد أن card هو رقم
            });
        
        // المبيعات
    Route::get('/sales', [\App\Http\Controllers\Pos\SalesController::class, 'index'])
         ->name('sales');        
        // المعاملات
            Route::get('/transactions', [\App\Http\Controllers\Pos\TransactionController::class, 'index'])
         ->name('transactions');
Route::get('/pos/cards/{card}/result', [InternetCardController::class, 'result'])
    ->name('pos.cards.result');

Route::get('/pos/cards/{card}/status', [\App\Http\Controllers\Pos\InternetCardcontroller::class, 'status'])
    ->name('pos.cards.status')
    ->middleware(['auth']);

        Route::get('/pos/{pos}/mikrotik-packages', [App\Http\Controllers\PosController::class, 'fetchPackagesFromMikrotik'])
    ->name('pos.mikrotik.packages');

Route::post(
    '/pos/cards/send-whatsapp/{card?}',
    [\App\Http\Controllers\Pos\InternetCardcontroller::class, 'sendViaWhatsApp']
)->name('pos.cards.send-whatsapp')->middleware(['auth']);

        // إعادة إرسال الكروت عبر واتساب
        Route::post('cards/send-whatsapp/{card}', [InternetCardController::class, 'sendViaWhatsApp'])
            ->name('cards.send-whatsapp')
            ->where('card', '[0-9]+');
Route::post('pos/cards/generate', [InternetCardController::class, 'store'])
    ->name('pos.cards.generate');

Route::get('pos/cards/result/{card}', [InternetCardController::class, 'result'])
    ->name('pos.cards.result');

Route::get('pos/cards/send-whatsapp/{card}', [InternetCardController::class, 'sendWhatsapp'])
    ->name('pos.cards.send-whatsapp');
// routes/web.php
Route::post('pos/cards/generate', [InternetCardController::class, 'generate'])->name('pos.cards.generate');
Route::get('pos/cards/result/{card}', [InternetCardController::class, 'result'])->name('pos.cards.result');

    });

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
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
