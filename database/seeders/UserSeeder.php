<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $userRoleId = Role::where('name', 'user')->first()->id;

        for ($i = 1; $i <= 2; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "admin$i@example.com",
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
            ]);
        }


        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "user$i@example.com",
                'password' => Hash::make('password'),
                'role_id' => $userRoleId,
            ]);
        }
    }
}

