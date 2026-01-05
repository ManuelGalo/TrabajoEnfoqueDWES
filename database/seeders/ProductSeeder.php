<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $allFiles = Storage::disk('public')->files('products');

        $marcas = ['Nova', 'Apex', 'Velo', 'Zenith', 'Hyper', 'Luna', 'Swift', 'Pulse'];
        $modelos = ['Runner', 'Street', 'Cloud', 'Impact', 'Flow', 'Elite', 'Retro', 'Flex'];
        $tecnologias = ['con suela de carbono', 'con amortiguación gel', 'tejido transpirable', 'impermeables Gore-Tex', 'edición limitada'];
        $categorias = ['deporte', 'casual', 'botas'];
        $generos = ['hombre', 'mujer', 'unisex'];
        $years = [2023, 2024, 2025];

        for ($i = 0; $i < 40; $i++) {
            $marca = $marcas[array_rand($marcas)];
            $modelo = $modelos[array_rand($modelos)];
            $year = $years[array_rand($years)];
            $name = "{$marca} {$modelo} {$year}";
            
            $category = $categorias[array_rand($categorias)];
            $gender = $generos[array_rand($generos)];
            $tech = $tecnologias[array_rand($tecnologias)];
            $price = round(rand(5900, 19900) / 100, 2);

            $product = Product::create([
                'name' => $name,
                'slug' => Str::slug($name . '-' . uniqid()),
                'description' => "Zapatilla {$tech}. Diseñadas para ofrecer el máximo confort y durabilidad en cada pisada. Perfectas para uso diario y ocasiones especiales.",
                'price' => $price,
                'category' => $category,
                'gender' => $gender,
                'is_active' => true,
                'images' => [],
            ]);

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

            $tallasPosibles = ['38', '39', '40', '41', '42', '43', '44', '45'];
            $numTallas = rand(3, 5);
            $tallasCheck = array_rand(array_flip($tallasPosibles), $numTallas);
            
            if (!is_array($tallasCheck)) {
                $tallasCheck = [$tallasCheck];
            }

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
