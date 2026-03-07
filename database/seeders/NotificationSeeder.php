<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [
            [
                'user_id' => 2,
                'type'    => 'new_order',
                'title'   => 'Nueva orden #NX-0001',
                'body'    => 'María López compró tu MacBook Pro M3 por $2,199',
                'data'    => json_encode(['order_id' => 1]),
                'read_at' => null,
            ],
            [
                'user_id' => 2,
                'type'    => 'new_review',
                'title'   => '⭐ Nueva reseña de 5 estrellas',
                'body'    => 'María López dejó una reseña de 5 estrellas en MacBook Pro M3',
                'data'    => json_encode(['product_id' => 1]),
                'read_at' => null,
            ],
            [
                'user_id' => 7,
                'type'    => 'order_shipped',
                'title'   => '📦 Tu orden fue enviada',
                'body'    => 'Tu MacBook Pro M3 está en camino. Tracking: DHL123456789',
                'data'    => json_encode(['order_id' => 1]),
                'read_at' => null,
            ],
            [
                'user_id' => 8,
                'type'    => 'order_completed',
                'title'   => '✅ Orden completada',
                'body'    => 'Tu orden de Nike Air Max 2025 fue entregada exitosamente',
                'data'    => json_encode(['order_id' => 2]),
                'read_at' => now(),
            ],
            [
                'user_id' => 4,
                'type'    => 'new_order',
                'title'   => 'Nueva orden #NX-0002',
                'body'    => 'Carlos Ruiz compró 2 pares de Nike Air Max 2025 por $378',
                'data'    => json_encode(['order_id' => 2]),
                'read_at' => now(),
            ],
            [
                'user_id' => 9,
                'type'    => 'payment_pending',
                'title'   => '⏳ Pago pendiente',
                'body'    => 'Tu orden del iPhone 16 Pro Max está pendiente de pago',
                'data'    => json_encode(['order_id' => 3]),
                'read_at' => null,
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}