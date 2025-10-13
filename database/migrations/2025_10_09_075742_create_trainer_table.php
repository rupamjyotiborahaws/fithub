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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('phone', 12)->nullable();
            $table->enum('gender', ['m', 'f', 'o']);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('height', 10)->nullable();
            $table->string('weight', 7)->nullable();
            $table->string('specialization', 500)->nullable();
            $table->string('certificate_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
