<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@demo.com',
            'phone' => '123456789',
            'password' => Hash::make('admin123'),
            'language' => 'English',
            'is_admin' => true,
        ]);
    }
}
