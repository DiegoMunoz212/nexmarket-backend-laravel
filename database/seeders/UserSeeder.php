<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'      => 'Admin NexMarket',
            'email'     => 'admin@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'phone'     => '+57 300 000 0001',
            'is_active' => true,
        ]);

        // Vendedores
        User::create([
            'name'      => 'TechStore MX',
            'email'     => 'techstore@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+52 55 1234 5678',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Apple Hub',
            'email'     => 'applehub@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+52 55 8765 4321',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'SneakerWorld',
            'email'     => 'sneakerworld@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+57 310 123 4567',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'AudioPro',
            'email'     => 'audiopro@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+57 315 987 6543',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'GameZone',
            'email'     => 'gamezone@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+54 11 2345 6789',
            'is_active' => true,
        ]);

        // Compradores
        User::create([
            'name'      => 'María López',
            'email'     => 'maria@test.com',
            'password'  => Hash::make('password123'),
            'role'      => 'buyer',
            'phone'     => '+57 320 111 2222',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Carlos Ruiz',
            'email'     => 'carlos@test.com',
            'password'  => Hash::make('password123'),
            'role'      => 'buyer',
            'phone'     => '+57 321 333 4444',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Ana Torres',
            'email'     => 'ana@test.com',
            'password'  => Hash::make('password123'),
            'role'      => 'buyer',
            'phone'     => '+52 55 5555 6666',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Diego Munoz',
            'email'     => 'diego@nexmarket.com',
            'password'  => Hash::make('password123'),
            'role'      => 'seller',
            'phone'     => '+57 300 777 8888',
            'is_active' => true,
        ]);
    }
}