<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->text('description');
            $table->integer('duration_days');
            $table->json('morning_schedule')->nullable();
            $table->json('afternoon_schedule')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
