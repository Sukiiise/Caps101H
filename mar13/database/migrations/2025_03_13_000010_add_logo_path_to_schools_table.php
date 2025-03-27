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
            // Modify schools table
            if (Schema::hasTable('schools')) {
                Schema::table('schools', function (Blueprint $table) {
                    if (!Schema::hasColumn('schools', 'logo_path')) {
                        $table->string('logo_path')->nullable()->after('contact_number');
                    }
                });
            }

            // Modify school_user table
            if (Schema::hasTable('school_user')) {
                Schema::table('school_user', function (Blueprint $table) {
                    // Add columns if they don't exist
                    if (!Schema::hasColumn('school_user', 'role')) {
                        $table->string('role')->default('student')->after('user_id');
                    }
                    
                    if (!Schema::hasColumn('school_user', 'is_active')) {
                        $table->boolean('is_active')->default(true)->after('role');
                    }
                    
                    // We'll skip modifying the unique constraint since it's needed for foreign keys
                });
            }
        } catch (\Exception $e) {
            Log::error('Migration failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function down()
    {
        try {
            // Remove columns from school_user
            if (Schema::hasTable('school_user')) {
                Schema::table('school_user', function (Blueprint $table) {
                    if (Schema::hasColumn('school_user', 'role')) {
                        $table->dropColumn('role');
                    }
                    if (Schema::hasColumn('school_user', 'is_active')) {
                        $table->dropColumn('is_active');
                    }
                });
            }

            // Remove logo_path from schools
            if (Schema::hasTable('schools')) {
                Schema::table('schools', function (Blueprint $table) {
                    if (Schema::hasColumn('schools', 'logo_path')) {
                        $table->dropColumn('logo_path');
                    }
                });
            }
        } catch (\Exception $e) {
            Log::error('Migration rollback failed: ' . $e->getMessage());
            throw $e;
        }
    }
};
