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
        $table->id()->primary();
        $table->string('recipients');
        $table->boolean('send_to_both')->default(true);
        $table->Integer('age_from')->unique();
        $table->Integer("age_to");
        $table->string('department');
        $table->string('locality');
        $table->string('message_title'); 
        $table->string('message_body'); 
        $table->binary("attachments"); 
        $table->datetime("notification_date_from");
        $table->datetime("notification_date_to"); 
        $table->boolean('send_by_email')->default(false);

        $table->timestamps(); //fixed

        $table->softDeletes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
