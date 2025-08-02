<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; // مطلوب لأنواع Doctrine DBAL
use Doctrine\DBAL\Types\StringType; // مطلوب لأنواع Doctrine DBAL عند استخدام ->change()

return new class extends Migration
{
    /**
     * تشغيل الترحيلات.
     */
    public function up(): void
    {
        // التأكد من تسجيل StringType مع Doctrine DBAL.
        // هذا حل شائع للمشاكل مع دالة `->change()` في بعض إصدارات Laravel/Doctrine DBAL.
        if (!Type::hasType('string')) {
            Type::addType('string', StringType::class);
        }

        Schema::table('point_of_sales', function (Blueprint $table) {
            // التحقق مما إذا كان العمود 'user_id' موجودًا بالفعل.
            if (!Schema::hasColumn('point_of_sales', 'user_id')) {
                // إذا كان 'user_id' غير موجود، قم بإضافته كـ unsignedBigInteger قابل للقيم الفارغة.
                // قم بتعديل 'id' إلى اسم عمود موجود ومناسب تريد أن يظهر 'user_id' بعده.
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            } else {
                // إذا كان 'user_id' موجودًا، فتابع لتغيير خصائصه (جعله قابلاً للقيم الفارغة).
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }

            // إضافة قيد المفتاح الأجنبي فقط إذا لم يكن موجودًا بالفعل.
            $foreignKeyName = $this->getForeignKeyName($table->getTable(), 'user_id');

            if (!$foreignKeyName) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users') // نفترض أن جدول المستخدمين هو 'users'
                      ->onDelete('set null'); // هذا هو القيد الذي يتطلب أن يكون العمود قابلاً للقيم الفارغة
            }
        });
    }

    /**
     * عكس الترحيلات.
     */
    public function down(): void
    {
        Schema::table('point_of_sales', function (Blueprint $table) {
            // أولاً، قم بحذف قيد المفتاح الأجنبي بأمان إذا كان موجودًا.
            // هذا أمر بالغ الأهمية لمنع الخطأ "Column cannot be NOT NULL: needed in a foreign key constraint SET NULL".
            if (Schema::hasColumn('point_of_sales', 'user_id')) {
                $foreignKeyName = $this->getForeignKeyName($table->getTable(), 'user_id');
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                } else {
                    // كخيار احتياطي: حاول إسقاط المفتاح الأجنبي بالاسم التقليدي لـ Laravel
                    // إذا فشلت دالة getForeignKeyName في العثور عليه.
                    try {
                        $table->dropForeign(['user_id']);
                    } catch (\Exception $e) {
                        // يمكنك تسجيل الخطأ هنا إذا كنت تريد، ولكن من الأفضل المتابعة
                        // إذا كان المفتاح الأجنبي غير موجود بالفعل.
                    }
                }
            }

            // ثانيًا، إذا كان العمود 'user_id' موجودًا، قم بحذفه.
            // هذا يفترض أن هذا الترحيل هو المسؤول عن إضافة العمود في المقام الأول.
            if (Schema::hasColumn('point_of_sales', 'user_id')) {
                $table->dropColumn('user_id');
            }

            // ملاحظة: لا تحاول جعل العمود 'NOT NULL' هنا إذا كان قيد المفتاح الأجنبي
            // في دالة up() يستخدم 'onDelete('set null')' لأنه سيتعارض.
            // إذا كان الغرض هو عكس قابلية القيم الفارغة فقط، يجب أن تتأكد
            // من عدم وجود قيم NULL في العمود قبل محاولة تغييرها إلى NOT NULL،
            // وهذا أمر معقد وغير موصى به مع قيود SET NULL.
        });
    }

    /**
     * الحصول على اسم المفتاح الأجنبي لجدول وعمود معين.
     * تستخدم هذه الدالة Doctrine DBAL لفحص مخطط قاعدة البيانات.
     *
     * @param string $table اسم الجدول.
     * @param string $column اسم العمود.
     * @return string|null اسم المفتاح الأجنبي إذا تم العثور عليه، وإلا null.
     */
    protected function getForeignKeyName($table, $column)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        $foreignKeys = $dbSchemaManager->listTableForeignKeys($table);

        foreach ($foreignKeys as $foreignKey) {
            // التحقق مما إذا كان العمود جزءًا من هذا المفتاح الأجنبي
            if (in_array($column, $foreignKey->getColumns())) {
                return $foreignKey->getName();
            }
        }

        return null;
    }
};