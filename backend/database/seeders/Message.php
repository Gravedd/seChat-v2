<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Message extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Message::factory()->count(50)->create();
    }
}
