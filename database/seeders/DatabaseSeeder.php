<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
     \App\Models\User::factory()->create([
        "name"=>"George Ogilo",
        "email"=>"gogilo2003@gmail.com",
        "password"=>bcrypt("Pablo!2013"),
     ]);
        // \App\Models\User::factory(10)->create();
        $this->call(ReadingSeeder::class);
    }
}
