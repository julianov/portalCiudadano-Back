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
        Schema::create('ciudadanos', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("prs_id")->unique(); # Código que identifica a la persona en BDU
            $table->string("cuil")->unique(); # Cuil del ciudadano que se utilizará como USUARIO del portal
            $table->string("password"); # clave ingresada por el ciudadano encriptada
            $table->string("nombre");
            $table->string("apellido");
            $table->timestamps();
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
        Schema::dropIfExists('ciudadanos');
    }
};
