<?php

namespace Database\Seeders;

use App\Models\Board;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BoardSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Board::factory(10)->create();

        Schema::disableForeignKeyConstraints();

        Board::query()->truncate();

        // Board::create([
        //     'id' => 1,
        //     'name' => 'Admin',
        //     'email' => 'admin@email.com',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$1Wn6fPL.7DqkZJYRFuatl.nrVxo/rtAmOQGkOfI1SusCjjZ08C8uW', // password //adminadmin
        //     'remember_token' => Str::random(10),
        // ]);

        Schema::enableForeignKeyConstraints();
    }
}
