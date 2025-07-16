<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // لا نحتاج dropForeign هنا لأن SHOW CREATE TABLE أظهر عدم وجود مفتاح أجنبي لـ pos_id
            // ولكن للتأكد من أن العمود nullable (على الرغم من أنه كذلك بالفعل) يمكننا استخدام change()
            $table->unsignedBigInteger('pos_id')->nullable()->change();

            // إضافة المفتاح الأجنبي الآن (لأنه غير موجود)
            // تأكد أنك لا تضيفه مرتين إذا كان موجوداً بالفعل.
            // يمكننا إضافة شرط ifNotExists() لكن هذا ليس متاحاً مباشرة للمفاتيح الأجنبية.
            // بدلاً من ذلك، Laravel سيقوم بالتحقق عند تشغيل الـ migration.
            $table->foreign('pos_id')->references('id')->on('point_of_sales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // حذف المفتاح الأجنبي إذا تراجعنا
            $table->dropForeign(['pos_id']); // هذه ستعمل الآن لأننا أضفناها في up()

            // إعادة العمود ليكون NOT NULL (إذا كان هذا هو المطلوب في حالة التراجع)
            // لكن بما أنه كان nullable في الأصل، ربما لا تحتاج this.
            // $table->unsignedBigInteger('pos_id')->nullable(false)->change();
        });
    }
};
