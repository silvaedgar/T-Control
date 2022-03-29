<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('document_type',['V','E','J','G','P']);
            $table->string('document',10); // rif o cedula
            $table->string('name',60);
            $table->string('contact', 80);
            $table->string('address', 200);
            $table->string('phone', 25);
            $table->float('balance',13,3)->default(0);
            $table->enum('status',['Activo','Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
