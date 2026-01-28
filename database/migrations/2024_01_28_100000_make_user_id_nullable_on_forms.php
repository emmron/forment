<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: Recreate table with nullable user_id
            Schema::table('forms', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->string('endpoint', 254)->change();
            });
        } else {
            // MySQL/PostgreSQL: Drop and recreate foreign key
            Schema::table('forms', function (Blueprint $table) {
                // Try to drop foreign key (may have different naming conventions)
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist or have different name
                    try {
                        $table->dropForeign('forms_user_id_foreign');
                    } catch (\Exception $e2) {
                        // Ignore if doesn't exist
                    }
                }
            });

            // Modify columns
            Schema::table('forms', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->string('endpoint', 254)->change();
            });

            // Re-add foreign key
            Schema::table('forms', function (Blueprint $table) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            Schema::table('forms', function (Blueprint $table) {
                $table->string('endpoint', 20)->change();
            });
        } else {
            Schema::table('forms', function (Blueprint $table) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    try {
                        $table->dropForeign('forms_user_id_foreign');
                    } catch (\Exception $e2) {
                        // Ignore
                    }
                }
            });

            Schema::table('forms', function (Blueprint $table) {
                $table->string('endpoint', 20)->change();
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            });
        }
    }
};
