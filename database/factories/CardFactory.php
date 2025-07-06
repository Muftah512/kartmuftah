<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected \$model = Card::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'package_id' => 1,
            'pos_id' => 1,
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ];
    }
}
