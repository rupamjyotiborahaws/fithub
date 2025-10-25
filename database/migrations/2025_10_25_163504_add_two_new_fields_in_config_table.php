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
            $table->dropColumn('monthly_fee');
            $table->dropColumn('registration_fee');
            $table->integer('membership_renewal_reminder')->default(0);
            $table->integer('membership_transfer_limit')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('config', function (Blueprint $table) {
            $table->dropColumn('membership_renewal_reminder');
            $table->dropColumn('membership_transfer_limit');
        });
    }
};
