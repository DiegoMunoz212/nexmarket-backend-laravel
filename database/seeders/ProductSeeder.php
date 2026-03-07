<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'user_id'        => 2, // TechStore MX
                'category_id'    => 2, // Laptops
                'name'           => 'MacBook Pro M3 14"',
                'description'    => 'El MacBook Pro más potente con chip M3, pantalla Liquid Retina XDR de 14 pulgadas, 16GB RAM y 512GB SSD.',
                'price'          => 2199.00,
                'discount_price' => 1999.00,
                'stock'          => 15,
                'sku'            => 'MBP-M3-14-512',
                'is_featured'    => true,
                'tags'           => 'apple,laptop,macbook,m3',
            ],
            [
                'user_id'        => 3, // Apple Hub
                'category_id'    => 2,
                'name'           => 'iPhone 16 Pro Max 256GB',
                'description'    => 'El iPhone más avanzado con chip A18 Pro, cámara de 48MP y pantalla Super Retina XDR de 6.7 pulgadas.',
                'price'          => 1099.00,
                'discount_price' => 999.00,
                'stock'          => 30,
                'sku'            => 'IPH-16PRO-256',
                'is_featured'    => true,
                'tags'           => 'apple,iphone,smartphone',
            ],
            [
                'user_id'        => 4, // SneakerWorld
                'category_id'    => 9,
                'name'           => 'Nike Air Max 2025',
                'description'    => 'Zapatillas Nike Air Max con tecnología de amortiguación Air, perfectas para running y uso diario.',
                'price'          => 189.00,
                'discount_price' => 159.00,
                'stock'          => 50,
                'sku'            => 'NIKE-AM-2025',
                'is_featured'    => false,
                'tags'           => 'nike,zapatillas,running,deportes',
            ],
            [
                'user_id'        => 5, // AudioPro
                'category_id'    => 3,
                'name'           => 'Sony WH-1000XM5',
                'description'    => 'Auriculares inalámbricos con cancelación de ruido líder en la industria, 30 horas de batería y audio de alta resolución.',
                'price'          => 349.00,
                'discount_price' => null,
                'stock'          => 25,
                'sku'            => 'SONY-WH1000XM5',
                'is_featured'    => true,
                'tags'           => 'sony,auriculares,bluetooth,cancelacion-ruido',
            ],
            [
                'user_id'        => 6, // GameZone
                'category_id'    => 26,
                'name'           => 'PS5 Slim Bundle',
                'description'    => 'PlayStation 5 Slim con control DualSense, 1TB SSD y dos juegos incluidos. La consola más potente de Sony.',
                'price'          => 499.00,
                'discount_price' => 459.00,
                'stock'          => 10,
                'sku'            => 'PS5-SLIM-BUNDLE',
                'is_featured'    => true,
                'tags'           => 'ps5,playstation,consola,gaming',
            ],
            [
                'user_id'        => 3, // Apple Hub
                'category_id'    => 2,
                'name'           => 'iPad Air M2 256GB',
                'description'    => 'iPad Air con chip M2, pantalla Liquid Retina de 11 pulgadas, compatible con Apple Pencil y Magic Keyboard.',
                'price'          => 749.00,
                'discount_price' => null,
                'stock'          => 20,
                'sku'            => 'IPAD-AIR-M2-256',
                'is_featured'    => false,
                'tags'           => 'apple,ipad,tablet',
            ],
            [
                'user_id'        => 2, // TechStore MX
                'category_id'    => 4,
                'name'           => 'Monitor LG 4K 27" 144Hz',
                'description'    => 'Monitor gaming LG UltraGear 4K con panel IPS, 144Hz, tiempo de respuesta 1ms y HDR600.',
                'price'          => 599.00,
                'discount_price' => 499.00,
                'stock'          => 12,
                'sku'            => 'LG-4K-27-144HZ',
                'is_featured'    => false,
                'tags'           => 'monitor,4k,gaming,lg',
            ],
            [
                'user_id'        => 3, // Apple Hub
                'category_id'    => 3,
                'name'           => 'AirPods Pro 3ra Gen',
                'description'    => 'AirPods Pro con cancelación activa de ruido, modo transparencia adaptable y hasta 30 horas de batería.',
                'price'          => 249.00,
                'discount_price' => null,
                'stock'          => 40,
                'sku'            => 'AIRPODS-PRO-3',
                'is_featured'    => true,
                'tags'           => 'apple,airpods,auriculares,bluetooth',
            ],
            [
                'user_id'        => 6, // GameZone
                'category_id'    => 28,
                'name'           => 'Silla Gamer RGB Pro',
                'description'    => 'Silla gaming ergonómica con iluminación RGB, soporte lumbar ajustable y reposabrazos 4D.',
                'price'          => 299.00,
                'discount_price' => 249.00,
                'stock'          => 8,
                'sku'            => 'SILLA-GAMER-RGB',
                'is_featured'    => false,
                'tags'           => 'silla,gaming,rgb,ergonomica',
            ],
            [
                'user_id'        => 10, // Diego
                'category_id'    => 1,
                'name'           => 'Cámara Sony Alpha A7 IV',
                'description'    => 'Cámara mirrorless full-frame con sensor de 33MP, video 4K 60fps y estabilización de imagen de 5 ejes.',
                'price'          => 2799.00,
                'discount_price' => 2499.00,
                'stock'          => 5,
                'sku'            => 'SONY-A7IV',
                'is_featured'    => true,
                'tags'           => 'sony,camara,mirrorless,fotografia',
            ],
            [
                'user_id'        => 4, // SneakerWorld
                'category_id'    => 11,
                'name'           => 'Adidas Ultraboost 24',
                'description'    => 'Zapatillas de running Adidas con tecnología Boost, upper Primeknit y suela Continental.',
                'price'          => 180.00,
                'discount_price' => 150.00,
                'stock'          => 35,
                'sku'            => 'ADIDAS-UB24',
                'is_featured'    => false,
                'tags'           => 'adidas,zapatillas,running,boost',
            ],
            [
                'user_id'        => 2, // TechStore MX
                'category_id'    => 5,
                'name'           => 'Teclado Mecánico Keychron K8',
                'description'    => 'Teclado mecánico inalámbrico con switches Gateron, retroiluminación RGB y compatible con Mac y Windows.',
                'price'          => 99.00,
                'discount_price' => null,
                'stock'          => 60,
                'sku'            => 'KEYCHRON-K8',
                'is_featured'    => false,
                'tags'           => 'teclado,mecanico,gaming,keychron',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'user_id'        => $productData['user_id'],
                'category_id'    => $productData['category_id'],
                'name'           => $productData['name'],
                'slug'           => Str::slug($productData['name']).'-'.Str::random(5),
                'description'    => $productData['description'],
                'price'          => $productData['price'],
                'discount_price' => $productData['discount_price'],
                'stock'          => $productData['stock'],
                'sku'            => $productData['sku'],
                'status'         => 'active',
                'is_featured'    => $productData['is_featured'],
                'tags'           => $productData['tags'],
                'views'          => rand(100, 5000),
            ]);

            // Imagen principal
            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => 'https://placehold.co/600x400?text='.urlencode($product->name),
                'is_primary' => true,
                'order'      => 1,
            ]);
        }
    }
}