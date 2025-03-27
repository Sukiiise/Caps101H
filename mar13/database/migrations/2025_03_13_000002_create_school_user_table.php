<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up()
    {
        try {
            if (!Schema::hasTable('school_user')) {
                Schema::create('school_user', function (Blueprint $table) {
                    $table->id();
                    // First create the columns
                    $table->unsignedBigInteger('school_id');
                    $table->unsignedBigInteger('user_id');
                    $table->string('role')->default('student');
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();

                    // Then add the foreign key constraints
                    $table->foreign('school_id')
                          ->references('id')
                          ->on('schools')
                          ->onDelete('cascade');
                          
                    $table->foreign('user_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('cascade');

                    // Add unique constraint
                    $table->unique(['school_id', 'user_id']);
                });
            }
        } catch (\Exception $e) {
            Log::error('Migration failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function down()
    {
        Schema::dropIfExists('school_user');
    }
};
