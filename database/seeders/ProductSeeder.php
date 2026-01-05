<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $allFiles = Storage::disk('public')->files('products');

        // Diccionarios de términos para calzado
        $marcas = ['Nova', 'Apex', 'Velo', 'Zenith', 'Hyper', 'Luna'];
        $modelos = ['Runner', 'Street', 'Cloud', 'Impact', 'Flow', 'Elite', 'Retro', 'Flex'];
        $tecnologias = ['con suela de carbono', 'con amortiguación gel', 'tejido transpirable', 'impermeables Gore-Tex', 'edición limitada'];
        $categorias = ['deporte', 'casual', 'botas'];
        $generos = ['hombre', 'mujer', 'unisex'];

        // Crear 40 productos
        for ($i = 0; $i < 40; $i++) {
            $marca = $faker->randomElement($marcas);
            $modelo = $faker->randomElement($modelos);
            $year = $faker->year();
            $name = "{$marca} {$modelo} {$year}";
            
            $category = $faker->randomElement($categorias);
            $gender = $faker->randomElement($generos);

            $product = Product::create([
                'name' => $name,
                'slug' => Str::slug($name . '-' . uniqid()),
                'description' => "Zapatilla " . $faker->randomElement($tecnologias) . ". Diseñadas para ofrecer el máximo confort y durabilidad en cada pisada. Ideales para " . $faker->sentence(10),
                'price' => $faker->randomFloat(2, 59, 199),
                'category' => $category,
                'gender' => $gender,
                'is_active' => true,
                'images' => [],
            ]);

            // Asignar imagen según categoría y género
            $catChar = match($category) {
                'botas' => 'b',
                'casual' => 'c',
                'deporte' => 'd',
                default => 'c'
            };

            $genChar = match($gender) {
                'hombre' => 'h',
                'mujer' => 'm',
                'unisex' => 'u',
                default => 'u'
            };

            $searchPattern = "products/" . $catChar . "-" . $genChar;
            $matchingImages = array_filter($allFiles, function($path) use ($searchPattern) {
                return str_starts_with(strtolower($path), strtolower($searchPattern));
            });

            if (!empty($matchingImages)) {
                $randomImage = $matchingImages[array_rand($matchingImages)];
                $product->update(['images' => [$randomImage]]);
            }

            // Generar tallas
            $tallasPosibles = ['38', '39', '40', '41', '42', '43', '44', '45'];
            $tallasCheck = (array) array_rand(array_flip($tallasPosibles), rand(3, 5));

            foreach ($tallasCheck as $talla) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $talla,
                    'stock' => rand(5, 20),
                ]);
            }
        }
    }
}
