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
        Schema::create('member_health_track', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('measure_date')->default(null);
            $table->float('weight')->default(0);
            $table->float('height')->default(0);
            $table->float('bmi')->default(0);
            $table->float('body_fat_percentage')->default(0);
            $table->float('muscle_mass')->default(0);
            $table->float('waist_circumference')->default(0);
            $table->float('hip_circumference')->default(0);
            $table->float('chest_circumference')->default(0);
            $table->float('thigh_circumference')->default(0);
            $table->float('arm_circumference')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_health_track');
    }
};
