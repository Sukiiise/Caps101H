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
        Schema::create('batch_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('course_batches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('registration_status');
            $table->string('delivery_mode');
            $table->string('provider_type');
            $table->string('region');
            $table->string('province');
            $table->string('congressional_district');
            $table->string('municipality');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_enrollments');
    }
};
