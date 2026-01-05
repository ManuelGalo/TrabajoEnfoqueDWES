<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $marcas = ['Nova', 'Apex', 'Velo', 'Zenith', 'Hyper', 'Luna'];
        $modelos = ['Runner', 'Street', 'Cloud', 'Impact', 'Flow', 'Elite', 'Retro', 'Flex'];
        $tecnologias = ['con suela de carbono', 'con amortiguación gel', 'tejido transpirable', 'impermeables Gore-Tex', 'edición limitada'];

        $marca = $marcas[array_rand($marcas)];
        $modelo = $modelos[array_rand($modelos)];
        $year = rand(2023, 2025);
        $name = "{$marca} {$modelo} {$year}";

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . uniqid()),
            'description' => "Zapatilla " . $tecnologias[array_rand($tecnologias)] . ". Diseñadas para ofrecer el máximo confort y durabilidad.",
            'price' => round(rand(5900, 19900) / 100, 2),
            'category' => ['deporte', 'casual', 'botas'][array_rand(['deporte', 'casual', 'botas'])],
            'gender' => ['hombre', 'mujer', 'unisex'][array_rand(['hombre', 'mujer', 'unisex'])],
            'is_active' => true,
            'images' => [],
        ];
    }
}
