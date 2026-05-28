<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE students MODIFY contact_no VARCHAR(20) NOT NULL');
        DB::statement("UPDATE students SET contact_no = CONCAT('0', contact_no) WHERE CHAR_LENGTH(contact_no) = 10");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE students MODIFY contact_no INT NOT NULL');
    }
};
