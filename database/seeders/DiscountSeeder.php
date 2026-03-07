<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'code'         => 'NEXOFF20',
                'type'         => 'percent',
                'value'        => 20,
                'min_purchase' => 100,
                'max_uses'     => 500,
                'used_count'   => 142,
                'starts_at'    => '2025-01-01',
                'ends_at'      => '2026-12-31',
                'is_active'    => true,
                'applies_to'   => 'all',
            ],
            [
                'code'         => 'TECH15',
                'type'         => 'percent',
                'value'        => 15,
                'min_purchase' => 200,
                'max_uses'     => 200,
                'used_count'   => 89,
                'starts_at'    => '2025-01-01',
                'ends_at'      => '2026-06-30',
                'is_active'    => true,
                'applies_to'   => 'category',
            ],
            [
                'code'         => 'PRIMERA50',
                'type'         => 'fixed',
                'value'        => 50,
                'min_purchase' => 150,
                'max_uses'     => 1000,
                'used_count'   => 234,
                'starts_at'    => '2025-01-01',
                'ends_at'      => '2026-12-31',
                'is_active'    => true,
                'applies_to'   => 'all',
            ],
            [
                'code'         => 'GAMER30',
                'type'         => 'percent',
                'value'        => 30,
                'min_purchase' => 300,
                'max_uses'     => 100,
                'used_count'   => 56,
                'starts_at'    => '2025-01-01',
                'ends_at'      => '2026-03-31',
                'is_active'    => true,
                'applies_to'   => 'category',
            ],
            [
                'code'         => 'ENVIOGRATIS',
                'type'         => 'shipping',
                'value'        => 0,
                'min_purchase' => 50,
                'max_uses'     => 2000,
                'used_count'   => 891,
                'starts_at'    => '2025-01-01',
                'ends_at'      => '2026-12-31',
                'is_active'    => true,
                'applies_to'   => 'all',
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }
    }
}