<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tax_id');
            $table->unsignedBigInteger('category_id');
            $table->string('code',20);
            $table->string('name',60);
            $table->float('cost_price',13,3)->default(0);  //Los default 0 es por permisos alguien puede crear pero no asignar precio
            $table->float('avg_cost',13,3)->default(0); // La moneda se considera de un archivo de configuracion
            $table->float('sale_price',13,3)->default(0);
            $table->float('stock',13,3)->default(0);
            $table->longText('image')->nullable();
            $table->enum('status',['Activo','Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
