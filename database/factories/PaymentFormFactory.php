<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;


class PaymentFormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $simbolo = $this->faker->unique()->randomElement(['Efectivo','Zelle','Paypal']);
        return [
            'user_id' => User::all()->random()->id,
            'payment_form' => $simbolo,
            'description' => $simbolo.' '.$this->faker->word(10)
        ];
    }
}
