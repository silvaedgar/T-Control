<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('coin_id');
            $table->float('rate_exchange',13,3)->default(1);
            $table->date('purchase_date')->default(now());
            $table->string('invoice',10)->nullable(); // Numero de factura
            $table->float('mount',13,3);
            $table->float('tax_mount',13,3);
            $table->float('paid_mount',13,3)->default(0);
            $table->enum('conditions',['Credito','Contado'])->default('Credito');
            $table->string('observations',150)->nullable();
            $table->enum('status',['Pendiente','Parcial','Cancelada','Anulada','Historico'])->default('Pendiente');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('coin_id')->references('id')->on('coins')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
