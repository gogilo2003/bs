<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Reading;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Simulate readings for the last 365 days
        $startDate = Carbon::now()->subYear();

        // disable foreign key checks to avoid issues with existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('readings')->truncate();
        // enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for ($day = 0; $day <= 365; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            // Decide if today has readings at all
            if ($faker->boolean(70)) { // ~70% chance of having at least one reading

                // Decide if today has FBS (only once in the morning)
                if ($faker->boolean(50)) { // 50% chance to take FBS
                    Reading::create([
                        'reading' => $faker->randomFloat(1, 0, 30),
                        'type' => 'fbs',
                        'read_at' => $currentDate->copy()->setTime(rand(5, 8), rand(0, 59)), // Morning
                        'user_id' => 1,
                    ]);
                }

                // Decide if today has RBS (can have multiple)
                if ($faker->boolean(60)) { // 60% chance to take RBS
                    $rbsCount = rand(1, 3); // Multiple readings possible
                    for ($i = 0; $i < $rbsCount; $i++) {
                        Reading::create([
                            'reading' => $faker->randomFloat(1, 0, 30),
                            'type' => 'rbs',
                            'read_at' => $currentDate->copy()->setTime(rand(9, 20), rand(0, 59)), // Any time after morning
                            'user_id' => 1,
                        ]);
                    }
                }
            }
        }
    }
}
