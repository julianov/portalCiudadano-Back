<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\DB;
use PDO;

class PlSqlService
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


	public function getNewNotifications($userId, $fecha_val, $departamento_val, $localidad_val, $edad_val, $destinatario_val)
	{
		
		$result = DB::select("SELECT CIUD_NOTIFICACIONES_PKG.NUEVAS_NOTIFICACIONES(:user_id, :fecha_val, :departamento_val, :localidad_val, :edad_val, :destinatario_val) as result FROM DUAL", [
			'user_id' => $userId,
			'fecha_val' => $fecha_val,
			'departamento_val' => $departamento_val,
			'localidad_val' => $localidad_val,
			'edad_val' => $edad_val,
			'destinatario_val' => $destinatario_val
		]);

		return $result[0]->result;
	}

	public function getAllNotifications ($userId, $fecha_val, $departamento_val, $localidad_val, $edad_val, $destinatario_val)
	{
		
		$result = DB::select("SELECT CIUD_NOTIFICACIONES_PKG.OBTENER_NOTIFICACIONES(:fecha_val, :departamento_val, :localidad_val, :edad_val, :destinatario_val) as result FROM DUAL", [
			'fecha_val' => $fecha_val,
			'departamento_val' => $departamento_val,
			'localidad_val' => $localidad_val,
			'edad_val' => $edad_val,
			'destinatario_val' => $destinatario_val
		]);

		return $result[0]->result;
	}

	public function getAllActiveNotifications($fechaActual)
	{
		
		$result = DB::select("SELECT CIUD_NOTIFICACIONES_PKG.TODAS_NOTIFICACIONES_ACTIVAS( :fecha_val) as result FROM DUAL", [
			'fecha_val' => $fechaActual
			
		]);

		return $result[0]->result;
	}

	public function getEmailsForNotification($min_fecha_nacimiento, $max_fecha_nacimiento, $localidad_id, $departamento_id, $tipo_de_usuario)
	{
		$result = DB::select("SELECT CIUD_NOTIFICACIONES_PKG.OBTENER_EMAIL_USUARIOS(:min_fecha_nacimiento, :max_fecha_nacimiento, :localidad_id, :departamento_id, :tipo_de_usuario) as result FROM DUAL", [
			'min_fecha_nacimiento' => $min_fecha_nacimiento,
			'max_fecha_nacimiento' => $max_fecha_nacimiento,
			'localidad_id' => $localidad_id,
			'departamento_id' => $departamento_id,
			'tipo_de_usuario'=> "'".$tipo_de_usuario."'",
		]);

		return $result[0]->result;

	}

	public function checkNotificationScope ($min_fecha_nacimiento, $max_fecha_nacimiento, $localidad_id, $departamento_id, $tipo_de_usuario)
	{
		$result = DB::select("SELECT CIUD_NOTIFICACIONES_PKG.OBTENER_ALCANCE_NOTIFICACION(:min_fecha_nacimiento, :max_fecha_nacimiento, :localidad_id, :departamento_id, :tipo_de_usuario) as result FROM DUAL", [
			'min_fecha_nacimiento' => $min_fecha_nacimiento,
			'max_fecha_nacimiento' => $max_fecha_nacimiento,
			'localidad_id' => $localidad_id,
			'departamento_id' => $departamento_id,
			'tipo_de_usuario'=> "'".$tipo_de_usuario."'",
		]);

		return $result[0]->result;
	}


	//MULTIMEDIA 



	public function notificationAttachment ( $file_path, $tamanio, $file_type, $file_extension, $table_id, $file_name)
	{
		
		$blob_file =file_get_contents($file_path) ;

		$inmuebleId = null;
		$procedimiento = 'CIUD_NOTIFICACIONES_PKG.NOTIFICACIONES_ADJUNTO';
        $parametros = [
			'p_file' => ["value" => &$blob_file, "type" => PDO::PARAM_LOB, "size" => $tamanio],                   
			'file_type' => $file_type,
			'file_extension' => $file_extension,
			'notification_table_id' => $table_id,
			'file_name' => $file_name,
            'P_multimedia_id' => ['value' => &$inmuebleId,'type' => PDO::PARAM_INT ]
        ];     
       
        $resultado = DB::executeProcedure($procedimiento, $parametros);

		return $inmuebleId;
	}



	public function getAttachmentFileName (string $table, int $multimedia_id ){


		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_CONT_NOMBRE(:p1, :p2) as result FROM DUAL", 
		[
			'p1' =>$table,
			'p2' =>$multimedia_id // Passing the output parameter by reference
		]);

		return $result[0]->result;
	}

	public function getUploadedFile (string $table, int $multimedia_id ){

		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_LEE_ARCHIVO(:p1, :p2) as result FROM DUAL", 
		[
			'p1' =>$table,
			'p2' =>$multimedia_id // Passing the output parameter by reference
		]);


		return $result[0]->result;
	}

	public function deleteUploadedFile (string $table, int $multimedia_id ){

		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.MULTIMEDIA_BORRA(:p1, :p2) as result FROM DUAL", 
		[
			'p1' =>$table,
			'p2' =>$multimedia_id // Passing the output parameter by reference
		]);

		if ($result[0]->result== $multimedia_id ){

			return true;


		}else{

			return false;


		}
	}


	public function deleteNotification(integer $notification_id){

		$res = DB::statement("DECLARE l_result BOOLEAN; BEGIN l_result := CIUD_NOTIFICACIONES_PKG.BORRAR_NOTIFICACION(:p_id); END;",
		[
			'p_id' => $notification_id,
			
		]);

	return $res;
	}

	public function notificationUsersReached(integer $notification_id){

		
		$result = DB::select("SELECT MULTIMEDIA.MMD_UTILIDADES_DGIN.OBTENER_USUARIOS_ALCANZADOS(:p_id) as result FROM DUAL", 
		[
			'p_id' =>$notification_id,
		]);

		return $res;
	}
	
	
}