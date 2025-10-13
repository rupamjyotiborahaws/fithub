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
        Schema::create('fee_payment_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->integer('membership_type');
            $table->string('for_month');
            $table->date('due_date');
            $table->decimal('amount', 8, 2)->default(1000.00);
            $table->date('paid_on')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->unsignedBigInteger('fee_payment_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payment_schedule');
    }
};
