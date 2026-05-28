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
        if (! Schema::hasColumn('students', 'user_account_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->unsignedBigInteger('user_account_id')->nullable()->after('id');
            });
        }

        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $foreignKeyExists = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'students')
            ->where('COLUMN_NAME', 'user_account_id')
            ->where('REFERENCED_TABLE_NAME', 'user_accounts')
            ->exists();

        if (! $foreignKeyExists) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreign('user_account_id')->references('id')->on('user_accounts');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
