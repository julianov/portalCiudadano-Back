<?php

use App\Models\Citizen;
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
		Schema::create('datos_contacto', function (Blueprint $table) {
			$table->uuid("id")->primary();
			$table->foreignIdFor(Citizen::class, "ciudadano_id")->constrained()->cascadeOnDelete();
			$table->string("email")->unique(); # Dirección de mail declarada por el ciudadano para notificaciones
			$table->string("email_token"); # Token de confirmación de email
			$table->string("fecha_nacimiento"); # Fecha de Nacimiento declarada por del ciudadano para notificaciones por rango etario
			$table->string("celular"); # Nro de celular declarado por el ciudadano para notificaciones (3dig caracteristica+7dig nro)
			$table->string("departamento_id"); # Id del departamento provincial
			$table->string("localidad_id"); # Id de la localidad provincial
			$table->string("domicilio"); # Calle del domicilio declarado por el ciudadano
			$table->string("numero"); # Nro de casa declarado por el ciudadano
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
		Schema::dropIfExists('datos_contacto');
	}
};
