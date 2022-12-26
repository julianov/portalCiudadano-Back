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
            $table->string("email");
            $table->string('password');
            $table->string('nombre');
            $table->string('apellido');

            $table->timestamp('email_verified_at')->nullable();

            #$table->timestamp("created_at");
            #$table->timestamp("updated_at");
            $table->timestamps(); //fixed 

            $table->rememberToken();
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
