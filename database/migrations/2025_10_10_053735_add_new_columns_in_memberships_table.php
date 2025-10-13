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
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('payment_type', 50)->after('duration_months');
            $table->decimal('admission_fee', 8, 2)->after('payment_type')->default(0);
            $table->decimal('monthly_fee', 8, 2)->after('admission_fee')->default(0);
            $table->decimal('one_time_fee', 8, 2)->after('monthly_fee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            //
        });
    }
};
