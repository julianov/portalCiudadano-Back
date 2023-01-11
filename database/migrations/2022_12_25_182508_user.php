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
<<<<<<< HEAD:database/migrations/2014_10_12_000000_create_users_table.php
        Schema::create('users', function (Blueprint $table) {
              
            $table->id();
            $table->bigInteger('cuil')->unique()->primary();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('password');
=======
        $a = Schema::create('users', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('cuil')->unique();
            $table->bigInteger('prs_id')->unique();
            $table->string("email");
            $table->string('password'); 
            $table->string('name'); #nombre de usuario
            $table->string('last_name'); #apellido de usuario
>>>>>>> e40bfe757f261588605a6116f2891d17defade28:database/migrations/2022_12_25_182508_user.php

            $table->timestamps(); //fixed
            $table->timestamp('email_verified_at')->nullable();

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
