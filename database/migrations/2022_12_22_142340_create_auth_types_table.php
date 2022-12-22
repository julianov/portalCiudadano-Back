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
        Schema::create('autenticacion_tipos', function (Blueprint $table) {
            $table->uuid("id")->primary(); # Identifica univocamente el registro
            $table->enum("descripcion", ["REGISTRADO","ANSES","AFIP","MIARGENTINA","PRESENCIAL"]);
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
        Schema::dropIfExists('autenticacion_tipos');
    }
};
