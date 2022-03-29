<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            for($cont=0; $cont<4; $cont++) {
                $productid = Product::all()->random()->id;
                $product = Product::find($productid);

                $price = $product->price;
                $tax = $product->tax * $price / 100;
                $qty = 2;
                $subtotal = $price * $qty;
                $user = User::all()->random()->id;
                Purchase::create([
                    'user_id' => User::all()->random()->id,
                    'purchase_date' => now(),
                    'mount' => $subtotal,
                    'tax' => $tax,
                    'status' => 'Pendiente'
                ]);
                $purchaseid = Purchase::all()->last()->id;
                PurchaseDetails::create([
                    'purchase_id' => $purchaseid,
                    'product_id' => $productid,
                    'item' => 1,
                    'quantity' => $qty,
                    'price' => $price,
                    'tax' => $tax,
                ]);
            }


        ];
    }
}
