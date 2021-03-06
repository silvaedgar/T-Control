<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('coin_id');
            $table->unsignedBigInteger('payment_form_id');
            $table->date('payment_date');
            $table->float('rate_exchange',12,4);
            $table->float('mount',12,2);
            $table->string('observations',100)->nullable();
            $table->enum('status',['Procesado','Anulado','Historico'])->default('Procesado');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('Cascade')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('Cascade')->onDelete('cascade');
            $table->foreign('coin_id')->references('id')->on('coins')->onUpdate('Cascade')->onDelete('cascade');
            $table->foreign('payment_form_id')->references('id')->on('payment_forms')->onUpdate('Cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_suppliers');
    }
}
