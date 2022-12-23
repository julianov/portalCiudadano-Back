<?php

use App\Models\AuthType;
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
		Schema::create('ciudadano_autenticacion', function (Blueprint $table) {
			$table->uuid("id")->primary(); # Identifica univocamente el registro
			$table->foreignIdFor(Citizen::class,
				"ciudadano_id")->constrained()->cascadeOnDelete(); # Id de la tabla ciudadano
			$table->foreignIdFor(AuthType::class,
				"autenticacion_tipo_id")->constrained()->cascadeOnDelete(); # Id de la tabla AUTENTICACION_TIPO
			$table->string("nivel"); # Nivel que le corresponde al tipo de autenticación
			$table->timestamp("fecha_autenticacion");
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
		Schema::dropIfExists('ciudadano_autenticacion');
	}
};
