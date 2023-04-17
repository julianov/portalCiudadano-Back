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
        Schema::create('procedure_data', function (Blueprint $table) {

            
            $table->id()->primary();
            $table->string("name"); 
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('userors')->onDelete('cascade');
            $table->bigInteger('actor_id');
            $table->foreign('actor_id')->references('id')->on('user_actors')->onDelete('cascade');
            $table->string("Json_forms_ids")->nullable(); 
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
        Schema::dropIfExists('procedure_data');

    }
};
