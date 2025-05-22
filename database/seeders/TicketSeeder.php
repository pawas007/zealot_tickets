<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::all();

        foreach ($users as $user) {

            $ticketsCount = rand(1, 10);

            for ($i = 0; $i < $ticketsCount; $i++) {
                Ticket::create([
                    'user_id' => $user->id,
                    'title' => $faker->sentence(6),
                    'description' => $faker->paragraph(),
                    'status' => $faker->randomElement(Ticket::STATUSES),
                ]);
            }
        }
    }
}
