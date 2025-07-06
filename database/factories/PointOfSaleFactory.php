<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PointOfSaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // ßáãÉ ãÑæÑ ÇÝÊÑÇÖíÉ
            'location' => $this->faker->address,
        ];
    }
}
