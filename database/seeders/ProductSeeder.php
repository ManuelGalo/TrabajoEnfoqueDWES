<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que la carpeta existe
        Storage::disk('public')->makeDirectory('products');

        // Creamos los productos y recorremos cada uno
        Product::factory(30)->create()->each(function ($product) {
            
            try {
    // Añadimos un User-Agent de navegador real para que no bloqueen la descarga
    $response = Http::withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ])->timeout(15)->get('picsum.photos');

    if ($response->successful()) {
        $fileName = 'products/' . Str::random(10) . '.jpg';
        
        // Guardamos el contenido usando el método body() que obtiene los bytes puros
        Storage::disk('public')->put($fileName, $response->body());
        
        $product->update(['images' => [$fileName]]);
    }
} catch (\Exception $e) {
    $this->command->warn("Fallo: " . $e->getMessage());
}
            // 2. GENERACIÓN DE TALLAS (Lo que ya tenías)
            $tallasDisponibles = ['38', '39', '40', '41', '42', '43', '44', '45'];
            $tallasElegidas = (array) array_rand(array_flip($tallasDisponibles), rand(3, 5));

            foreach ($tallasElegidas as $talla) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $talla,
                    'stock' => rand(5, 20),
                ]);
            }
        }); // <-- Aquí se cierra el function($product) y el each()
    }
}
