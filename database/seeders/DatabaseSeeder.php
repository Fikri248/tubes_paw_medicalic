<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ObatSeeder::class,
            OrdersSeeder::class,
            TransaksiSeeder::class
        ]);
    }
}
