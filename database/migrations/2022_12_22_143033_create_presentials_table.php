<?php

use App\Models\CitizenAuth;
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
		Schema::create('presencial', function (Blueprint $table) {
			$table->foreignIdFor(CitizenAuth::class,
				"ciudadano_autenticacion_id")->constrained("ciudadano_autenticacion"); # Id de la tabla CIUDADANO_AUTENTICACION
			$table->string("dni_frente"); #
			$table->string("dni_dorso"); #
			$table->string("foto"); #
			$table->string("actor_id"); # Id del actor que registra la identidad presencial obtenido del ws actores
			$table->date("fecha_autenticacion");
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
		Schema::dropIfExists('presencial');
	}
};
