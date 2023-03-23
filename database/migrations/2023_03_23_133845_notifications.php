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
            $table->string('recipients');
            $table->Integer('send_to_both')->default(0); //if notifications goes to both o
            $table->Integer('age_from')->unique();
            $table->Integer("age_to");
            $table->Integer('department');
            $table->Integer('locality');
            $table->string('message_title'); 
            $table->string('message_body'); 
            $table->binary("attachments"); 
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
