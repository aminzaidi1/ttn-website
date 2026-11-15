<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SiteSeeder::class);

        // Placeholder content from the design prototype, so every page has something to show.
        // None of it is real: replace it in the admin before launch.
        if (class_exists(ContentSeeder::class)) {
            $this->call(ContentSeeder::class);
        }
    }
}
