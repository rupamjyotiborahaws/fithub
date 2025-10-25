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
        Schema::table('fee_payment_schedule_history', function (Blueprint $table) {
            $table->boolean('latest')->default(0)->after('fee_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_payment_schedule_history', function (Blueprint $table) {
            $table->dropColumn('latest');
        });
    }
};
