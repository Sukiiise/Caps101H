<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('position');
            $table->string('department')->nullable();
            $table->string('employment_status')->default('active');
            $table->date('date_hired')->nullable();
            $table->string('employee_id')->unique()->nullable();
            $table->text('qualifications')->nullable();
            $table->text('responsibilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure a user can only be staff once per school
            $table->unique(['user_id', 'school_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
