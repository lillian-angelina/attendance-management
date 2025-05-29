<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(), // 認証済み
            ]
        );
    }
}