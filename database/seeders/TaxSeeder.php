<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Facades\DB;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tax::factory(2)->create();
    }
}
