<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'image_path')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE students MODIFY image_path VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        //
    }
};
