<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ProductGroup;


class ProductCategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'group_id' => ProductGroup::all()->random()->id,
            'description' => $this->faker->unique()->randomElement(['Harina','Azucar','Analgesicos','Antialergicos',
                    'Mayonesa','Salsa de Tomate','Arroz','Mostaza','Pan','Queso','Margarinas',
                    'Aceite','Cafe','Carnes','Aves','Pescado','Pasta','Ricota','Desodorantes','Champu',
                    'Jabon en Polvo','Jabon de Tocador','Galletas','Bombillos','Velas','Chorizos','Tomate',
                    'Cebolla','Pimenton','Granos','Refrescos','Sobres','Protector Gastrico','Condimentos','Cigarros'])
        ];
    }
}
