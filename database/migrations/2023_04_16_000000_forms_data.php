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
        Schema::create('forms_data', function (Blueprint $table) {

            $table->id()->primary();

            $table->bigInteger('forms_units_id');
            $table->foreign('forms_units_id')->references('id')->on('forms_units')->onDelete('cascade');
            
            $table->string("json_form_fields_data")->nullable(); 
            $table->enum('status', ['pending', 'pause', 'canceled','approved'])->default('both'); // Se agrega el método default() con el valor "both"
            $table->datetime("date_approved");

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
        Schema::dropIfExists('forms_data');

    }
};
