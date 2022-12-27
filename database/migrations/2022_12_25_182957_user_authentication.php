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
       
        Schema::create('user_authentication', function (Blueprint $table) {

            $table->id()->primary();
            
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger("authentication_types_id");
            $table->foreign('authentication_types_id')->references('id')->on('authentication_types')->onDelete('cascade');

            $table->string("auth_level"); # Nivel que le corresponde al tipo de autenticación
         
            $table->timestamps(); //fixed 
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
        Schema::dropIfExists('user_authentication');

    }
};
