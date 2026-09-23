<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{

    public function run(): void
    {
        // DB::table('users')->insert([
        //     'name' => Str::random(10),
        //     'email' => Str::random(10) . '@gmail.com',
        //     'password' => Hash::make('password'),
        // ]);
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin1@gmail.com',
            'password' => Hash::make('12345678'), // hoặc bcrypt('12345678')
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
