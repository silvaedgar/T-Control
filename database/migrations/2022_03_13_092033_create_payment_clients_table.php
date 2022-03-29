<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('coin_id');
            $table->unsignedBigInteger('payment_form_id');
            $table->date('payment_date')->default(now());
            $table->float('rate_exchange');
            $table->float('mount');
            $table->string('observations',100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('Cascade')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onUpdate('Cascade')->onDelete('cascade');
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
        Schema::dropIfExists('payment_clients');
    }
}
