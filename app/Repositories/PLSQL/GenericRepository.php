<?php

namespace App\Repositories\PLSQL;

use Illuminate\Support\Facades\DB;
use PDO;

class GenericRepository
{

    public function __construct()

	{
		

	}

	public function getLastId ($table_name){

		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.OBTENER_ULTIMO_ID(:table_name) as result FROM DUAL", 
		[
			'table_name' => $table_name
		]);
		
		return $result[0]->result;
	}

	public function getCuilFromDNI ($dni){
		
		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.OBTENER_USER_POR_DNI(:dni) as result FROM DUAL", 
		[
			'dni' => $dni
		]);
		
		return $result[0]->result;
	}

    public function insertarFila(string $table_name, string $columns, string $values): bool{


		$res = DB::statement("DECLARE l_result BOOLEAN; BEGIN l_result := CIUD_UTILIDADES_PKG.INSERTAR_FILA(:table_name, :columns, :values); END;",
            [
                'table_name' => $table_name,
                'columns' => $columns,
                'values' => $values,
            ]);
        
		return $res;

	}

	public function getRow( string $table, string $column_name, string $column_value): mixed{

		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_" . $table . "(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);
		$json = json_decode($result[0]->result);
	
		return $json;
	}

	public function updateFila(string $table_name, string $columns, string $values): mixed{

			$res = DB::statement("DECLARE l_result BOOLEAN; BEGIN l_result := CIUD_UTILIDADES_PKG.MODIFICAR_FILAR(:p_nombre_tabla, :p_valores_columnas, :p_clausula_where); END;",
            [
                'p_nombre_tabla' => $table_name,
                'p_valores_columnas' => $columns,
                'p_clausula_where' => $values,
            ]);

		return $res;
	}
	
	
}