<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Electrónica',
                'icon'        => '💻',
                'description' => 'Tecnología, gadgets y dispositivos electrónicos',
                'children'    => ['Smartphones', 'Laptops', 'Audio', 'Monitores', 'Accesorios'],
            ],
            [
                'name'        => 'Moda',
                'icon'        => '👟',
                'description' => 'Ropa, calzado y accesorios de moda',
                'children'    => ['Zapatos', 'Ropa Hombre', 'Ropa Mujer', 'Bolsos', 'Relojes'],
            ],
            [
                'name'        => 'Hogar',
                'icon'        => '🏠',
                'description' => 'Artículos para el hogar y decoración',
                'children'    => ['Muebles', 'Cocina', 'Decoración', 'Jardín'],
            ],
            [
                'name'        => 'Deportes',
                'icon'        => '⚽',
                'description' => 'Equipos y ropa deportiva',
                'children'    => ['Fútbol', 'Running', 'Gym', 'Natación'],
            ],
            [
                'name'        => 'Gaming',
                'icon'        => '🎮',
                'description' => 'Videojuegos, consolas y accesorios',
                'children'    => ['Consolas', 'Videojuegos', 'Accesorios Gaming', 'PC Gaming'],
            ],
            [
                'name'        => 'Libros',
                'icon'        => '📚',
                'description' => 'Libros físicos y digitales',
                'children'    => ['Ficción', 'Tecnología', 'Negocios', 'Educación'],
            ],
            [
                'name'        => 'Belleza',
                'icon'        => '💄',
                'description' => 'Productos de belleza y cuidado personal',
                'children'    => ['Skincare', 'Maquillaje', 'Perfumes', 'Cuidado Cabello'],
            ],
        ];

        foreach ($categories as $cat) {
            $parent = Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'icon'        => $cat['icon'],
                'is_active'   => true,
            ]);

            foreach ($cat['children'] as $child) {
                Category::create([
                    'name'      => $child,
                    'slug'      => Str::slug($child).'-'.Str::random(4),
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}