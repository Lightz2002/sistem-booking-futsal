<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'password' => Hash::make('123'),
                'email' => 'admin@gmail.com',
                'phone_no' => '08129922772',
                'remember_token' => Str::random(10),
                'role' => 'admin'
            ],
            [
                'name' => 'customer1',
                'password' => Hash::make('123'),
                'email' => 'customer1@gmail.com',
                'phone_no' => '08129922772',
                'remember_token' => Str::random(10),
                'role' => 'customer'
            ]
        ]);
        //
    }
}
