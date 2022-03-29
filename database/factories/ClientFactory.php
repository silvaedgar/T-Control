<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $indice = $this->faker->unique()->randomElement([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26]);

        $data = [['Airam',0],['Anny Gonzalez',2.9],['Barbara Melissa',0],['Carlos Ricar',83.33],
            ['Chuchi',0],['Claudio Falco',28],['Corina',14.8],['Deyanira Huerta',198.49],['Enrique',22.85],
            ['Gladys Ramirez',0],['Gustavo Parraga',20.60],['Isabel Delgado',15.28],['Jessica',29.75],
            ['Jonathan',4.36],['Beba',2.4],['Laurence',3.95],['Mama Juan Carlos',38.55],['Manuel Paris',4.92],
            ['Maria Cristina',0],['Maria Pena',0],['Maximita',6.76],['Nestor Delgado',5.3],['Osleidys',26.97],
            ['Senora Carmen',12.9],['Senora Marilyn',18.2],['Senora Oly',62.59],['Yoli Bolanos',68.53]];
        $cliente = $this->faker->unique()->randomElement($data);
        $document = $this->faker->unique()->randomElement(['100','101','102','103','104','105','106','107','108','109',
                    '110','111','1120','1130','1140','1150','1160','1170','1180','1190',
                    '20','21','22','23','24','25','26']);
        $phone = "04".$this->faker->randomElement(['14','24','16','26','12'])."-".$this->faker->numerify('###-##-##');
        return [
            'user_id' => User::all()->random()->id,
            'document_type' => $this->faker->randomElement(['J','G','P','V','E']),
            'document' => $document,
            'names' => $cliente[0],
            'address' => $this->faker->address(),
            'balance' => $cliente[1]            //
        ];
        // 'balance' =>$this->faker->numerify('##')            //
    }
}
