<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        $data = [['Alejandro Chorizos',0],['Distribuidora ITC',10],['Cafe Flor de Patria',0],
            ['Gerardo Delgado',20],['Guillermo Beltran',42.9],['Jose Andres Pescado',0],['Comercializadora KJK',0],
            ['Pedro Queso',10],['Rayma Leon',7],['Simon Cigarros',20],['Supermarket 81 C.A',40],
            ['Suplymos',18.72]];
        $supplier = $this->faker->unique()->randomElement($data);

            // $balances = [0,10,0,77,46.8,48,15,70,7,70,40,18.72];
        // $proveedor = $proveedores[$index];
        // $balance = $balances[$index];

        return [
            'user_id' => User::all()->random()->id,
            'document_type' => $this->faker->randomElement(['J','G','P','V','E']),
            'document' => strval($this->faker->buildingNumber),
            'name' => $supplier[0],
            'contact' => $this->faker->name,
            'phone' => $this->faker->phoneNumber(15),
            'address' => $this->faker->address,
            'balance' => $supplier[1],
        ];
    }

}
