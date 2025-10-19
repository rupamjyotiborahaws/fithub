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
        Schema::create('memberdetails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('age')->nullable();
            $table->enum('gender', ['m', 'f', 'o'])->nullable();
            $table->date('dob')->default('0000-00-00');
            $table->text('fitness_goals')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->integer('membership_type')->default(1);
            $table->date('membership_start_date')->nullable();
            $table->time('membership_schedule_time')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->float('discount')->default(0);
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberdetails');
    }
};
