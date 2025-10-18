<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'nombre'      => $name,
            'descripcion' => $this->faker->sentence(),
            'precio'      => $this->faker->randomFloat(2, 5, 90),
            'imagen'      => 'https://picsum.photos/seed/'.Str::slug($name).'/600/400',
            'stock'       => 10,
            'slug'        => Str::slug($name),
        ];
    }
}