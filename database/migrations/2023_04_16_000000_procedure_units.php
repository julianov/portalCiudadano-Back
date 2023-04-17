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
        Schema::create('procedure_units', function (Blueprint $table) {

            
            $table->id()->primary();
            $table->string("name"); 
            $table->bigInteger('actor_id');
            $table->foreign('actor_id')->references('id')->on('user_actors')->onDelete('cascade');
            $table->string("Json_forms_numbers")->nullable(); 
            $table->boolean('active')->default(false);

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
        Schema::dropIfExists('procedure_units');

    }
};
