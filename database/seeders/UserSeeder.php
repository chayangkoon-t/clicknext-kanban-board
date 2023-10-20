<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Schema::disableForeignKeyConstraints();

        User::query()->truncate();

        User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$1Wn6fPL.7DqkZJYRFuatl.nrVxo/rtAmOQGkOfI1SusCjjZ08C8uW', // password //adminadmin
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'id' => 2,
            'name' => 'Admin2',
            'email' => 'admin2@email.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$1Wn6fPL.7DqkZJYRFuatl.nrVxo/rtAmOQGkOfI1SusCjjZ08C8uW', // password //adminadmin
            'remember_token' => Str::random(10),
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
