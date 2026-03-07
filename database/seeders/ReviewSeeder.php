<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'user_id'    => 7,
                'product_id' => 1,
                'order_id'   => 1,
                'rating'     => 5,
                'comment'    => 'Excelente MacBook, increíblemente rápido y la pantalla es espectacular. Vale cada centavo.',
            ],
            [
                'user_id'    => 8,
                'product_id' => 3,
                'order_id'   => 2,
                'rating'     => 5,
                'comment'    => 'Las Nike Air Max son muy cómodas, perfectas para correr. Las recomiendo totalmente.',
            ],
            [
                'user_id'    => 7,
                'product_id' => 4,
                'order_id'   => 4,
                'rating'     => 5,
                'comment'    => 'Los mejores auriculares que he tenido. La cancelación de ruido es increíble.',
            ],
            [
                'user_id'    => 8,
                'product_id' => 5,
                'order_id'   => 5,
                'rating'     => 5,
                'comment'    => 'PS5 increíble, los juegos se ven hermosos y el control DualSense es una maravilla.',
            ],
            [
                'user_id'    => 9,
                'product_id' => 2,
                'order_id'   => 3,
                'rating'     => 4,
                'comment'    => 'iPhone 16 Pro Max es fantástico, la cámara es de otro nivel. Solo le daría 4 por el precio.',
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}