<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class ProductGroupFactory extends Factory
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
            'description' => $this->faker->unique()->randomElement(['Viveres','Carniceria','Medicinas',
                'Lacteos','Panaderia','Charcuteria','Pescaderia','Cosmeticos','Miscelaneos','Bebidas',
                'Chucherias','Dulces','Verduras'])            //
        ];
    }
}
