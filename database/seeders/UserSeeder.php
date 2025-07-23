<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        DB::table('user')->insert([
            [
                'id_role' => 1,
                'username' => 'Erlin',
                'email' => 'erlin@gmail.com',
                'password' => Hash::make('123456789'),
                'otp' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
