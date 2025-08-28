<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // change this to your admin email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('P@ssword123!'), // change this to a secure password
                'role' => 'admin',
            ]
        );
    }
}
