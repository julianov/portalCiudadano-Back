<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contact', function (Blueprint $table) {

            $table->id()->primary();

            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string("birthday"); # Fecha de Nacimiento declarada por del ciudadano para notificaciones por rango etario
            $table->string("cellphone_number"); # Nro de celular declarado por el ciudadano para notificaciones (3dig caracteristica+7dig nro)
            $table->string("department_id"); # Id del departamento provincial
            $table->string("locality_id"); # Id de la localidad provincial
            $table->string("address_street"); # Calle del domicilio declarado por el ciudadano
            $table->string("address_number"); # Nro de casa declarado por el ciudadano
            $table->string("apartment"); # Nro de departamento o vivienda, ejemplo 16 F

            $table->timestamps(); //fixed
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('cellphone_number_verified_at')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_contact');

    }
};
