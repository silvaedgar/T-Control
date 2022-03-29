<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Coin;

class CurrencyValueFactory extends Factory
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
            'coin_id' => Coin::all()->random()->id,
            'base_currency_id' => Coin::all()->random()->id,
            'purchase_price' => $this->faker->buildingNumber,
            'sale_price' => $this->faker->buildingNumber
        ];
    }
}
