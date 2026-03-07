<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $wishlists = [
            ['user_id' => 7, 'product_id' => 2],
            ['user_id' => 7, 'product_id' => 5],
            ['user_id' => 7, 'product_id' => 8],
            ['user_id' => 8, 'product_id' => 1],
            ['user_id' => 8, 'product_id' => 4],
            ['user_id' => 8, 'product_id' => 7],
            ['user_id' => 9, 'product_id' => 3],
            ['user_id' => 9, 'product_id' => 6],
            ['user_id' => 9, 'product_id' => 10],
        ];

        foreach ($wishlists as $wishlist) {
            Wishlist::create($wishlist);
        }
    }
}