<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TaxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $porcentaje =  $this->faker->randomElement([0,16]);
        $porcentaje = 0;
        return [
            'user_id' => User::all()->random()->id,
            'percent' => $porcentaje,
            'description' => ($porcentaje == 0 ? 'Exento' : 'Gravamen 16%')
            //
        ];
    }
}
