<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class CoinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => $this->faker->word(20),
            'symbol' => $this->faker->unique()->randomElement(['BsD','$']),
            'calc_currency_purchase' => $this->faker->randomElement(['S','N']),
            'calc_currency_sale' => $this->faker->randomElement(['S','N']),
            'base_currency' => $this->faker->randomElement(['S','N']),
        ];
    }
}
