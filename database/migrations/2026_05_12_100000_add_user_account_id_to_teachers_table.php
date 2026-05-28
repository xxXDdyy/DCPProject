<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'user_account_id')) {
                $table->unsignedBigInteger('user_account_id')->nullable()->after('id');
                $table->foreign('user_account_id')->references('id')->on('user_accounts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'user_account_id')) {
                $table->dropForeign(['user_account_id']);
                $table->dropColumn('user_account_id');
            }
        });
    }
};
