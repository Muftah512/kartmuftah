public function definition()
{
    return [
        'type' => $this->faker->randomElement(['debit', 'credit']),
        'amount' => $this->faker->randomFloat(2, 10, 500),
        'pos_id' => \App\Models\PointOfSale::factory(),
        'user_id' => \App\Models\User::factory(),
        'description' => $this->faker->sentence,
    ];
}
