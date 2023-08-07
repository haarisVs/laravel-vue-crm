<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        Users::query()->updateOrCreate(['email' => 'admin@admin.com'], [
            'firstName' => 'admin',
            'lastName' => 'admin',
            'phoneNumber' => '0000000000000',
            'role' => 'admin',
            'emailVerified' => true,
            'password' => Hash::make('password')
        ]);
    }
}
