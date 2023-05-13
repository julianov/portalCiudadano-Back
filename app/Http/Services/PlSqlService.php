<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;


class PlSqlService
{

    public function __construct()

	{
		

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


	public function getNotifications($fecha_val, $departamento_val, $localidad_val, $edad_val, $destinatario_val)
	{
		
		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.OBTENER_NOTIFICATIONS(:fecha_val, :departamento_val, :localidad_val, :edad_val, :destinatario_val) as result FROM DUAL", [
			'fecha_val' => $fecha_val,
			'departamento_val' => $departamento_val,
			'localidad_val' => $localidad_val,
			'edad_val' => $edad_val,
			'destinatario_val' => $destinatario_val
		]);

		return $result[0]->result;
	}

	public function getEmailsForNotification($min_fecha_nacimiento, $max_fecha_nacimiento, $localidad_id, $departamento_id, $tipo_de_usuario)
	{
		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.OBTENER_EMAIL_USUARIOS(:min_fecha_nacimiento, :max_fecha_nacimiento, :localidad_id, :departamento_id, :tipo_de_usuario) as result FROM DUAL", [
			'min_fecha_nacimiento' => $min_fecha_nacimiento,
			'max_fecha_nacimiento' => $max_fecha_nacimiento,
			'localidad_id' => $localidad_id,
			'departamento_id' => $departamento_id,
			'tipo_de_usuario'=> "'".$tipo_de_usuario."'",
		]);

		return $result[0]->result;

	}


	//MULTIMEDIA 


	public function insertFile (string $table_name, string $file_type, string $file_type_extension, string $file_name, string $file_path)
	{
	
		$multimedia_id=-1;

		$table="MULTIMEDIA.MMD_CIUD_".$table_name."_DOC";

		//file type puede ser DOC si es un documento o IMG si es una imagen

		//multimedia id es el output

		//MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_INSERTA_ARCHIVO('CIUDADANOS',:table_name,:file_type, :file_type_description,:file_name,:file,:multimedia_id)

		$blob_file = (self::getDocumentFromFront($file_path) );
		
		$res = DB::statement("BEGIN MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_INSERTA_ARCHIVO(:p1, :p2, :p3, :p4, :p5, :p6, :p7); END;", [
			'CIUDADANOS',
			$table,
			$file_type,
			$file_type_extension,
			$file_name,
			$blob_file,
			&$multimedia_id // Passing the output parameter by reference
		]);

		dd($multimedia_id);
		return $multimedia_id;

	}


	function convertFileToBlob(UploadedFile $file) // Se espera que $file sea una instancia de UploadedFile
	{
		$extension = $file->getClientOriginalExtension();
		$blob = null;

		if ($extension === 'pdf') {
			$pdf = new Pdf($file->getPathname());
			$image = Image::make($pdf->getImageData())->encode('png');
			$blob = $image->getEncoded();
		} elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
			$image = Image::make($file)->encode('png');
			$blob = $image->getEncoded();
		} else {
			return "Bad extension";
		}

		return $blob;
	}


	function getDocumentFromFront(string $rutaImagen){
		// Verificar si el archivo existe
		if (file_exists($rutaImagen)) {
			// Leer el contenido del archivo
			$contenidoImagen = file_get_contents($rutaImagen);

			// Convertir el contenido a formato base64
			$blob = base64_encode($contenidoImagen);

			// Imprimir el blob
			return $blob;
		}
		else{
			return "bad data";
		}
	}

	public function getUploadedFile (string $table, int $multimedia_id ){

		$res = DB::statement("BEGIN MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_LEE_ARCHIVO(:p1, :p2); END;", [
			$table,
			$multimedia_id // Passing the output parameter by reference
		]);

		return $res;
	}


}