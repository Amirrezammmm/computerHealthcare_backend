<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Manager',
                'password'  => Hash::make('admin123456'),
                'role'      => 'manager',
                'is_active' => true,
            ]
        );
    }
}
