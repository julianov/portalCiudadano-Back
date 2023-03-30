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
        $a = Schema::create('notifications', function (Blueprint $table) {

            $table->id()->primary();
            $table->enum('recipients', ['citizen', 'actor', 'both'])->default('both'); // Se agrega el método default() con el valor "both"
            $table->integer('age_from')->nullable(); // se agrega nullable() para hacerlo opcional
            $table->integer("age_to")->nullable(); // se agrega nullable() para hacerlo opcional
            $table->integer('department')->nullable(); // se agrega nullable() para hacerlo opcional
            $table->integer('locality')->nullable(); // se agrega nullable() para hacerlo opcional
            $table->string('message_title');
            $table->string('message_body')->nullable(); // se agrega nullable() para hacerlo opcional
            $table->binary("attachments")->nullable(); // se agrega nullable() para hacerlo opcional
            $table->datetime("notification_date_from");
            $table->datetime("notification_date_to");
            $table->boolean('send_by_email')->default(false);

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

        Schema::dropIfExists('notifications');

    }
};
