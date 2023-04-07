<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('coin_id');
            $table->float('rate_exchange',12,4);
            $table->date('sale_date');
            $table->string('invoice',10)->nullable(); // Numero de factura no es obligatorio en las primeras de cambios
            $table->float('mount',12,2);
            $table->float('associated_costs',12,2)->default(0);

            $table->float('tax_mount',12,2);
            $table->float('paid_mount',12,2)->default(0);

            $table->enum('conditions',['Credito','Contado'])->default('Credito');
            $table->string('observations',150)->nullable();
            $table->enum('status',['Pendiente','Parcial','Cancelada','Anulada','Historico'])->default('Pendiente');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('sales');
    }
}
