<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), // 認証済み
        ]);

       // $users = User::factory()->count(20)->create([
      //      'name' => fn() => fake('ja_JP')->name(),
       //     'email' => fn() => fake('ja_JP')->unique()->safeEmail(),
        //]);

        // 勤怠を各ユーザーに1件ずつ紐付けて作成
       // foreach ($users as $user) {
       //     Attendance::factory()->create([
       //         'user_id' => $user->id,
       //     ]);
       // }
    }
}