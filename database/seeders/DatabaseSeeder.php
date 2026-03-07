<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            DiscountSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            NotificationSeeder::class,
            ConversationSeeder::class,
        ]);
    }
}