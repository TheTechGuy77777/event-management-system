<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eventplug.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@eventplug.com',
                'password' => Hash::make('Admin@1234'),
                'role' => 'admin',
                'is_active' => true,
                'is_banned' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
