<?php

namespace Database\Seeders;

use App\Modules\Tgbot\Models\TgUser;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(NoticeSeeder::class);
    }
}
