<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_accounts')) {
            return;
        }

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE user_accounts MODIFY username VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE user_accounts MODIFY email VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE user_accounts MODIFY password VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE user_accounts MODIFY role VARCHAR(50) NOT NULL');
        }

        $adminPasswordHash = '$2y$10$lo1P0NhaRrmA3T4/fat1XuOXcxeCtSoztdHUA0te6vsSRj31ikulq';
        $now = now();
        $admin = DB::table('user_accounts')->where('username', 'admin')->first();

        if ($admin) {
            DB::table('user_accounts')
                ->where('username', 'admin')
                ->update([
                    'email' => 'admin@example.com',
                    'password' => $adminPasswordHash,
                    'role' => 'admin',
                    'is_active' => 1,
                    'must_change_password' => 0,
                    'updated_at' => $now,
                ]);

            return;
        }

        DB::table('user_accounts')->insert([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => $adminPasswordHash,
            'role' => 'admin',
            'is_active' => 1,
            'must_change_password' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        //
    }
};
