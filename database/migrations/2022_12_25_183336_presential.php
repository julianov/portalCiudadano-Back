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
        Schema::create('presential', function (Blueprint $table) {

            $table->id()->primary();

            $table->bigInteger("user_authentication_id");
            $table->foreign('user_authentication_id')->references('id')->on('user_authentication')->onDelete('cascade');

            $table->binary("dni_front"); #
            $table->binary("dni_back"); #
            $table->binary("user_photo"); #
            $table->bigInteger("actor_id"); # Id del actor que registra la identidad presencial obtenido del ws actores
            $table->foreign('actor_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps(); //fixed


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presential');

    }
};
