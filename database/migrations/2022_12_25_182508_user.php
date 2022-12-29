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
        $a = Schema::create('users', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('cuil')->unique();
            $table->bigInteger('prs_id')->unique();
            $table->string('name'); #nombre de usuario
            $table->string('last_name'); #apellido de usuario
            $table->string("password");
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
        Schema::dropIfExists('users');

    }
};
