use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\DashboardController;
use App\Http\Controllers\Pos\CardController;
use App\Http\Controllers\Pos\SalesController;
use App\Http\Controllers\Pos\TransactionController;

Route::prefix('pos')
    ->name('pos.')
    ->middleware(['auth', 'role:pos', 'ensure.active'])
    ->group(function() {
        // لوحة التحكم
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // إدارة البطاقات
        Route::prefix('cards')
            ->name('cards.')
            ->group(function() {
                Route::get('generate', [CardController::class, 'generateForm'])->name('generate');
                Route::post('generate', [CardController::class, 'generate'])->name('generate.submit');
                
                Route::get('recharge', [CardController::class, 'rechargeForm'])->name('recharge');
                Route::post('recharge', [CardController::class, 'recharge'])->name('recharge.submit');
                
                Route::get('result/{card}', [CardController::class, 'result'])
                    ->name('result')
                    ->where('card', '[0-9]+'); // التأكد أن card هو رقم
            });
        
        // المبيعات
        Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
        
        // المعاملات
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        // إعادة إرسال الكروت عبر واتساب
        Route::post('cards/send-whatsapp/{card}', [CardController::class, 'sendViaWhatsApp'])
            ->name('cards.send-whatsapp')
            ->where('card', '[0-9]+');
    });