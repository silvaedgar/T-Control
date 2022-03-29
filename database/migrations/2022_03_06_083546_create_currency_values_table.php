<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('coin_id');
            $table->unsignedBigInteger('base_currency_id');
            $table->datetime('date_value')->default(now());
            $table->float('purchase_price',12,3);  // precio en relacion a la moneda base (Bs)
            $table->float('sale_price',12,3);
            $table->enum('status',['Activo','Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('base_currency_id')->references('id')->on('coins')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_values');
    }
}
