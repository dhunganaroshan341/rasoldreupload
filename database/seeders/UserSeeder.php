<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::updateOrCreate([
            'name' => 'ras admin',
            'email' => 'ras@admin.com',
            'phone' => '1234567890',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('RasHasPass@123#'), // Replace 'password' with your desired password
            'remember_token' => Str::random(10),
        ]);
    }
}