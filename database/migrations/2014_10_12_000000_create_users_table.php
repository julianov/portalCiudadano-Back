<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->uuid()->primary();
			$table->string("cuil")->unique(); # Cuil del ciudadano que se utilizará como USUARIO del portal
			$table->string("prs_id")->unique(); # Código que identifica a la persona en BDU
			$table->string('nombre');
			$table->string('apellido');
			$table->string('email');
			$table->string('password');
			$table->rememberToken();
			$table->timestamp('email_verified_at')->nullable();
			$table->softDeletes();
			$table->timestamps();
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
