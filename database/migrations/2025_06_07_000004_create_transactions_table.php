<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // الرابط إلى نقطة البيع
            $table->foreignId('pos_id')
                  ->constrained('point_of_sales')
                  ->cascadeOnDelete();

            // نوع المعاملة (credit أو debit)
            $table->enum('type', ['credit','debit']);

            // المبلغ
            $table->decimal('amount', 15, 2);

            // وصف اختياري
            $table->text('description')->nullable();

            // الرصيد بعد المعاملة
            $table->decimal('balance_after', 15, 2);

            // طريقة الدفع
            $table->enum('payment_method', ['cash','bank_transfer','card'])
                  ->nullable();

            // ملاحظات إضافية
            $table->text('notes')->nullable();

            // مرجع خارجي (مثلاً رقم الفاتورة)
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
