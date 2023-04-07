<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 50);
            $table->string('symbol', '3');
            $table->enum('calc_currency_purchase', ['S', 'N'])->default('N'); // estable si es la moneda base para los calculos de costos y precios
            $table->enum('calc_currency_sale', ['S', 'N'])->default('N'); // estable si es la moneda base para los calculos de costos y precios
            $table->enum('base_currency', ['S', 'N'])->default('N'); // indica si es la moneda base (Es decir Bs)
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
