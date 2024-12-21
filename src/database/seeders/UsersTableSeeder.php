<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データを削除
        DB::table('users')->delete();

        // IDのオートインクリメントをリセット
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        DB::table('users')->insert([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'), // パスワードを暗号化
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
