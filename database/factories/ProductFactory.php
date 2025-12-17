<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true) . ' Pro';
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(15),
            'price' => $this->faker->randomFloat(2, 45, 180), 
            'stock' => $this->faker->numberBetween(0, 20),
            'category' => $this->faker->randomElement(['deporte', 'casual', 'botas']),
            'is_active' => true,
            'images' => [], 
        ];
    }
}
