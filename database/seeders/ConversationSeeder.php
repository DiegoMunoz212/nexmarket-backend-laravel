<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = [
            [
                'buyer_id'   => 7, // María López
                'seller_id'  => 2, // TechStore MX
                'product_id' => 1, // MacBook Pro
                'messages'   => [
                    [
                        'sender_id' => 7,
                        'body'      => 'Hola, ¿el MacBook Pro tiene garantía oficial de Apple?',
                    ],
                    [
                        'sender_id' => 2,
                        'body'      => '¡Hola María! Sí, incluye garantía oficial de Apple por 1 año y podemos extenderla a 2 años.',
                    ],
                    [
                        'sender_id' => 7,
                        'body'      => 'Perfecto, ¿hacen envío a Bogotá?',
                    ],
                    [
                        'sender_id' => 2,
                        'body'      => 'Sí, enviamos a toda Colombia con DHL. El envío es gratis para pedidos mayores a $500.',
                    ],
                    [
                        'sender_id' => 7,
                        'body'      => 'Excelente, voy a hacer el pedido ahora mismo. ¡Gracias!',
                    ],
                ],
            ],
            [
                'buyer_id'   => 8, // Carlos Ruiz
                'seller_id'  => 5, // AudioPro
                'product_id' => 4, // Sony WH-1000XM5
                'messages'   => [
                    [
                        'sender_id' => 8,
                        'body'      => 'Buenas tardes, ¿los auriculares Sony vienen con estuche de viaje?',
                    ],
                    [
                        'sender_id' => 5,
                        'body'      => '¡Buenas! Sí, vienen con estuche rígido premium, cable USB-C y adaptador de avión.',
                    ],
                    [
                        'sender_id' => 8,
                        'body'      => '¿Tienen disponible en color negro y plateado?',
                    ],
                    [
                        'sender_id' => 5,
                        'body'      => 'Tenemos disponible en negro, plateado y azul medianoche. ¿Cuál prefieres?',
                    ],
                ],
            ],
            [
                'buyer_id'   => 9, // Ana Torres
                'seller_id'  => 6, // GameZone
                'product_id' => 5, // PS5
                'messages'   => [
                    [
                        'sender_id' => 9,
                        'body'      => 'Hola, ¿el PS5 Slim incluye algún juego?',
                    ],
                    [
                        'sender_id' => 6,
                        'body'      => '¡Hola Ana! El bundle incluye Spider-Man 2 y Ratchet & Clank. ¡Son dos juegazos!',
                    ],
                    [
                        'sender_id' => 9,
                        'body'      => 'Genial, ¿hacen envíos a España?',
                    ],
                    [
                        'sender_id' => 6,
                        'body'      => 'Sí, enviamos a España con tiempo de entrega de 5-7 días hábiles.',
                    ],
                ],
            ],
        ];

        foreach ($conversations as $convData) {
            $conversation = Conversation::create([
                'buyer_id'        => $convData['buyer_id'],
                'seller_id'       => $convData['seller_id'],
                'product_id'      => $convData['product_id'],
                'last_message_at' => now(),
            ]);

            foreach ($convData['messages'] as $msgData) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $msgData['sender_id'],
                    'body'            => $msgData['body'],
                    'read_at'         => now(),
                ]);
            }
        }
    }
}