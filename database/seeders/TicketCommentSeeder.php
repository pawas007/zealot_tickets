<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TicketCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::all();
        $tickets = Ticket::all();

        foreach ($tickets as $ticket) {
            $commentsCount = random_int(8, 10);
            for ($i = 0; $i < $commentsCount; $i++) {
                $user = $users->random();

                TicketComment::create([
                    'ticket_id' => $ticket->id,
                    'user_id'   => $user->id,
                    'text'      => $faker->paragraph,
                ]);
            }
        }
    }
}
