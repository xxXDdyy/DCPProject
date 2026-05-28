<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('teachers', 'image_path')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('contact_no');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'image_path')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};
