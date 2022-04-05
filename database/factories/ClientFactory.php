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

        $data = [['Airam',0],['Anny Gonzalez',13.63],['Barbara Melissa',0],['Carlos Ricar',92.63],
            ['Chuchi',10.10],['Claudio Falco',0],['Corina',14.8],['Deyanira Huerta',102.66],
            ['Enrique',56.03],['Gladys Ramirez',37.41],['Gustavo Parraga',20.60],['Isabel Delgado',16.28],
            ['Jessica',29.75],['Jonathan',15.31],['Beba',2.4],['Laurence',24],['Mama Juan Carlos',48.06],
            ['Manuel Paris',24.12],['Maria Cristina',0],['Maria Pena',2.80],['Maximita',0],['Nestor Delgado',7.3],
            ['Osleidys',46.62],['Senora Oly',70.29],['Yoli Bolanos',112.38]];
        $cliente = $this->faker->unique()->randomElement($data);
        $document = $this->faker->unique()->randomElement(['100','101','102','103','104','105','106','107','108',
                    '109','110','111','1120','1130','1140','1150','1160','1170','1180','1190',
                    '20','21','22','23','24']);
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
