<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add additional optional fields to the packages table.
 *
 * The original packages table only contained name, price, size_mb,
 * validity_days and mikrotik_profile.  New functionality requires
 * storing status, download and upload speeds, device limit and a
 * JSON array of feature flags.  This migration adds those columns
 * with sensible defaults and makes them nullable for backward
 * compatibility.
 */
class AddAdditionalFieldsToPackagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Only add columns if they do not already exist to prevent duplicate column errors.
            if (!Schema::hasColumn('packages', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('mikrotik_profile');
            }
            if (!Schema::hasColumn('packages', 'download_speed')) {
                $table->unsignedInteger('download_speed')->nullable()->after('status');
            }
            if (!Schema::hasColumn('packages', 'upload_speed')) {
                $table->unsignedInteger('upload_speed')->nullable()->after('download_speed');
            }
            if (!Schema::hasColumn('packages', 'device_limit')) {
                $table->unsignedInteger('device_limit')->nullable()->after('upload_speed');
            }
            if (!Schema::hasColumn('packages', 'features')) {
                $table->json('features')->nullable()->after('device_limit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['status', 'download_speed', 'upload_speed', 'device_limit', 'features']);
        });
    }
}