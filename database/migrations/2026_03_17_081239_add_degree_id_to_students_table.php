<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'degree_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('degree_id')->nullable();
            });
        }

        // Normalize existing schema/data before adding the foreign key.
        DB::statement('ALTER TABLE students MODIFY degree_id BIGINT UNSIGNED NULL');
        DB::statement('UPDATE students SET degree_id = NULL WHERE degree_id IS NOT NULL AND degree_id NOT IN (SELECT degree_id FROM degrees)');

        $foreignKeyExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'students')
            ->where('COLUMN_NAME', 'degree_id')
            ->where('REFERENCED_TABLE_NAME', 'degrees')
            ->where('REFERENCED_COLUMN_NAME', 'degree_id')
            ->exists();

        if ($foreignKeyExists) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('degree_id')
                ->references('degree_id')
                ->on('degrees')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foreignKeyExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'students')
            ->where('COLUMN_NAME', 'degree_id')
            ->where('REFERENCED_TABLE_NAME', 'degrees')
            ->where('REFERENCED_COLUMN_NAME', 'degree_id')
            ->exists();

        if (! $foreignKeyExists) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['degree_id']);
        });
    }
};
