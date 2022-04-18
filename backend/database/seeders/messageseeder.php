<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class messageseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::factory()->count(50)->create();
    }
}
