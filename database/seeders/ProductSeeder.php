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
        // 1. Obtenemos todos los nombres de archivos reales que tienes en la carpeta
        // Esto evita errores si falta algún número o nombre
        $allFiles = Storage::disk('public')->files('products');

        // 2. Creamos 40 productos aleatorios
        Product::factory(40)->create()->each(function ($product) use ($allFiles) {
            
            // Mapeo de categorías y géneros a tus iniciales de archivo
            $catChar = match($product->category) {
                'botas' => 'b',
                'casual' => 'c',
                'deporte' => 'd',
                default => 'c'
            };

            $genChar = match($product->gender) {
                'hombre' => 'h',
                'mujer' => 'm',
                'unisex' => 'u',
                default => 'u'
            };

            // Prefijo que buscamos, ej: "products/b-h"
            $searchPattern = "products/" . $catChar . "-" . $genChar;

            // Filtramos el array de archivos para buscar los que empiecen por ese patrón
            $matchingImages = array_filter($allFiles, function($path) use ($searchPattern) {
                // Pasamos a minúsculas para evitar problemas con la "C" mayúscula de tus archivos
                return str_starts_with(strtolower($path), strtolower($searchPattern));
            });

            // Si encontramos imágenes que coincidan, asignamos una al azar
            if (!empty($matchingImages)) {
                $randomImage = $matchingImages[array_rand($matchingImages)];
                $product->update([
                    'images' => [$randomImage]
                ]);
            }

            // 3. Generar tallas (Manteniendo tu lógica anterior)
            $tallasPosibles = ['38', '39', '40', '41', '42', '43', '44', '45'];
            $tallasCheck = (array) array_rand(array_flip($tallasPosibles), rand(3, 5));

            foreach ($tallasCheck as $talla) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $talla,
                    'stock' => rand(5, 20),
                ]);
            }
        });
    }
}
