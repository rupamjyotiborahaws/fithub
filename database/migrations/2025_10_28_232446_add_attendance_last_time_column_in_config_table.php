<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->time('attendance_opening_time')->nullable()->after('membership_transfer_limit');
            $table->time('attendance_last_time')->nullable()->after('attendance_opening_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('attendance_opening_time');
            $table->dropColumn('attendance_last_time');
        });
    }
};
