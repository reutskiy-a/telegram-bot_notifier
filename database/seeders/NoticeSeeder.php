<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();


        for ($i = 0; $i < 100; $i++) {
            $time = $faker->time('H:i');

            DB::table('notices')->insert([
                'chat_id' => -4968760432,
                'user_id' => 1840038185,
                'day_of_week' => json_encode([0, 1, 2, 3, 4, 5, 6]),
                'time' => $time,
                'text' => "time: $time | " . $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
