<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShipmentTracking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'user_id'          => 7, // María López
                'discount_id'      => null,
                'status'           => 'shipped',
                'subtotal'         => 2199.00,
                'discount_amount'  => 0,
                'shipping_cost'    => 0,
                'total'            => 2199.00,
                'shipping_address' => 'Calle 123 #45-67, Bogotá, Colombia',
                'payment_method'   => 'card',
                'payment_status'   => 'completed',
                'notes'            => 'Entregar en horario de oficina',
                'items'            => [
                    ['product_id' => 1, 'quantity' => 1, 'unit_price' => 2199.00],
                ],
                'tracking' => [
                    'carrier'         => 'DHL',
                    'tracking_number' => 'DHL123456789',
                    'status'          => 'in_transit',
                    'location'        => 'Bogotá, Colombia',
                ],
            ],
            [
                'user_id'          => 8, // Carlos Ruiz
                'discount_id'      => 1,
                'status'           => 'delivered',
                'subtotal'         => 378.00,
                'discount_amount'  => 75.60,
                'shipping_cost'    => 0,
                'total'            => 302.40,
                'shipping_address' => 'Av. Reforma 456, Ciudad de México, México',
                'payment_method'   => 'paypal',
                'payment_status'   => 'completed',
                'notes'            => null,
                'items'            => [
                    ['product_id' => 3, 'quantity' => 2, 'unit_price' => 189.00],
                ],
                'tracking' => [
                    'carrier'         => 'FedEx',
                    'tracking_number' => 'FDX987654321',
                    'status'          => 'delivered',
                    'location'        => 'Ciudad de México',
                ],
            ],
            [
                'user_id'          => 9, // Ana Torres
                'discount_id'      => null,
                'status'           => 'pending',
                'subtotal'         => 1099.00,
                'discount_amount'  => 0,
                'shipping_cost'    => 0,
                'total'            => 1099.00,
                'shipping_address' => 'Calle Gran Vía 78, Madrid, España',
                'payment_method'   => 'card',
                'payment_status'   => 'pending',
                'notes'            => 'Llamar antes de entregar',
                'items'            => [
                    ['product_id' => 2, 'quantity' => 1, 'unit_price' => 1099.00],
                ],
                'tracking' => null,
            ],
            [
                'user_id'          => 7, // María López
                'discount_id'      => 3,
                'status'           => 'delivered',
                'subtotal'         => 349.00,
                'discount_amount'  => 50.00,
                'shipping_cost'    => 0,
                'total'            => 299.00,
                'shipping_address' => 'Calle 123 #45-67, Bogotá, Colombia',
                'payment_method'   => 'card',
                'payment_status'   => 'completed',
                'notes'            => null,
                'items'            => [
                    ['product_id' => 4, 'quantity' => 1, 'unit_price' => 349.00],
                ],
                'tracking' => [
                    'carrier'         => 'Servientrega',
                    'tracking_number' => 'SRV123456',
                    'status'          => 'delivered',
                    'location'        => 'Bogotá, Colombia',
                ],
            ],
            [
                'user_id'          => 8, // Carlos Ruiz
                'discount_id'      => null,
                'status'           => 'confirmed',
                'subtotal'         => 499.00,
                'discount_amount'  => 0,
                'shipping_cost'    => 0,
                'total'            => 499.00,
                'shipping_address' => 'Av. Reforma 456, Ciudad de México, México',
                'payment_method'   => 'transfer',
                'payment_status'   => 'completed',
                'notes'            => null,
                'items'            => [
                    ['product_id' => 5, 'quantity' => 1, 'unit_price' => 499.00],
                ],
                'tracking' => [
                    'carrier'         => 'DHL',
                    'tracking_number' => 'DHL456789123',
                    'status'          => 'picked_up',
                    'location'        => 'Ciudad de México',
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $order = Order::create([
                'user_id'          => $orderData['user_id'],
                'discount_id'      => $orderData['discount_id'],
                'status'           => $orderData['status'],
                'subtotal'         => $orderData['subtotal'],
                'discount_amount'  => $orderData['discount_amount'],
                'shipping_cost'    => $orderData['shipping_cost'],
                'total'            => $orderData['total'],
                'shipping_address' => $orderData['shipping_address'],
                'payment_method'   => $orderData['payment_method'],
                'payment_status'   => $orderData['payment_status'],
                'notes'            => $orderData['notes'],
            ]);

            // Crear items
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['unit_price'] * $item['quantity'],
                ]);
            }

            // Crear tracking
            if ($orderData['tracking']) {
                ShipmentTracking::create([
                    'order_id'        => $order->id,
                    'carrier'         => $orderData['tracking']['carrier'],
                    'tracking_number' => $orderData['tracking']['tracking_number'],
                    'status'          => $orderData['tracking']['status'],
                    'location'        => $orderData['tracking']['location'],
                    'estimated_at'    => now()->addDays(3),
                ]);
            }

            // Crear pago si está completado
            if ($orderData['payment_status'] === 'completed') {
                Payment::create([
                    'order_id'       => $order->id,
                    'amount'         => $orderData['total'],
                    'currency'       => 'USD',
                    'status'         => 'completed',
                    'transaction_id' => 'TXN'.strtoupper(substr(md5(rand()), 0, 10)),
                    'paid_at'        => now(),
                ]);
            }
        }
    }
}