<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100000; $i++) {
            \App\Models\Message::create([
                'sender_id' => mt_rand(1, 8),
                'receiver_id' => mt_rand(1, 8),
                'message' => Str::random(40),
                'readed' => false,
            ]);
        }
    }
}
