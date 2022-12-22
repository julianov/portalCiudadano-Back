<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            \App\Models\AuthType::create(["descripcion" => $type]);
        }
    }
}
