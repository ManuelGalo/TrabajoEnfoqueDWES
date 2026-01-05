<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
   public function definition(): array
{
    // Diccionarios de términos para calzado
    $marcas = ['Nova', 'Apex', 'Velo', 'Zenith', 'Hyper', 'Luna'];
    $modelos = ['Runner', 'Street', 'Cloud', 'Impact', 'Flow', 'Elite', 'Retro', 'Flex'];
    $tecnologias = ['con suela de carbono', 'con amortiguación gel', 'tejido transpirable', 'impermeables Gore-Tex', 'edición limitada'];

    $marca = $this->faker->randomElement($marcas);
    $modelo = $this->faker->randomElement($modelos);
    $year = $this->faker->year();
    
    $name = "{$marca} {$modelo} {$year}";

    return [
        'name' => $name,
        'slug' => \Illuminate\Support\Str::slug($name),
        'description' => "Zapatilla " . $this->faker->randomElement($tecnologias) . ". " . 
                         "Diseñadas para ofrecer el máximo confort y durabilidad en cada pisada. " . 
                         "Ideales para " . $this->faker->sentence(10),
        'price' => $this->faker->randomFloat(2, 59, 199),
        'category' => $this->faker->randomElement(['deporte', 'casual', 'botas']),
        'gender' => $this->faker->randomElement(['hombre', 'mujer', 'unisex']),
        'is_active' => true,
        'images' => [], // El Seeder se encarga de rellenar esto con tus fotos
    ];
}

}
