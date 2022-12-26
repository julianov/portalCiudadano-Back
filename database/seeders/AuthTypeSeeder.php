<?php

namespace Database\Seeders;

use App\Models\AuthType;
use Illuminate\Database\Seeder;

class AuthTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$types = ["REGISTRADO", "ANSES", "AFIP", "MIARGENTINA", "PRESENCIAL"];
		foreach ($types as $type) {
			AuthType::create(["descripcion" => $type]);
		}
	}
}
