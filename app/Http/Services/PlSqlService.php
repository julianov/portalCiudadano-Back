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


	public function insertarFilaConAttachment(string $table_name, string $columns, array $validated): bool
	{
		// Conectar a la base de datos
		$conn = oci_connect('username', 'password', 'localhost/orcl');
		
		// Obtener los datos de la imagen en base64 y decodificarlos
		$image_base64 = $validated['attachment'];
		$image_data = base64_decode($image_base64);
		
		// Crear un objeto BLOB y escribir los datos de la imagen
		$image_blob = oci_new_descriptor($conn, OCI_D_LOB);
		$image_blob->write($image_data);
		
		// Llamar a la función plsql y pasar los argumentos
		$stmt = oci_parse($conn, 'BEGIN :result := INSERTAR_FILA(:table_name, :columns, :image_blob); END;');
		$result = false;
		oci_bind_by_name($stmt, ':result', $result, -1, OCI_B_BOOLEAN);
		oci_bind_by_name($stmt, ':table_name', $table_name);
		oci_bind_by_name($stmt, ':columns', $columns);
		oci_bind_by_name($stmt, ':image_blob', $image_blob, -1, OCI_B_BLOB);
		oci_execute($stmt);
		
		// Cerrar la conexión y el objeto BLOB
		oci_free_statement($stmt);
		$image_blob->free();
		oci_close($conn);
		
		return $result;
	}

	public function insertarFilaConAttachmentV2(string $table_name, string $columns, array $validated): bool

	{
		// Create a connection to the Oracle database
		$conn = oci_connect('username', 'password', 'hostname:port/service_name');

		// Prepare the SQL statement to call the function
		$sql = "BEGIN :result := INSERTAR_NOTIFICACIONES_AT(
			:p_recipients,
			:p_age_from,
			:p_age_to,
			:p_department_id,
			:p_locality_id,
			:p_message_title,
			:p_message_body,
			:p_attachment_type,
			:p_attachment,
			:p_notification_date_from,
			:p_notification_date_to,
			:p_send_by_email
		); END;";

		// Prepare the statement handle
		$stmt = oci_parse($conn, $sql);

		// Bind the input parameters
		oci_bind_by_name($stmt, ':p_recipients', $p_recipients);
		oci_bind_by_name($stmt, ':p_age_from', $p_age_from);
		oci_bind_by_name($stmt, ':p_age_to', $p_age_to);
		oci_bind_by_name($stmt, ':p_department_id', $p_department_id);
		oci_bind_by_name($stmt, ':p_locality_id', $p_locality_id);
		oci_bind_by_name($stmt, ':p_message_title', $p_message_title);
		oci_bind_by_name($stmt, ':p_message_body', $p_message_body);
		oci_bind_by_name($stmt, ':p_attachment_type', $p_attachment_type);
		oci_bind_by_name($stmt, ':p_attachment', $p_attachment, -1, OCI_B_BLOB);
		oci_bind_by_name($stmt, ':p_notification_date_from', $p_notification_date_from, SQLT_DAT);
		oci_bind_by_name($stmt, ':p_notification_date_to', $p_notification_date_to, SQLT_DAT);
		oci_bind_by_name($stmt, ':p_send_by_email', $p_send_by_email);

		// Bind the output parameter
		oci_bind_by_name($stmt, ':result', $result, 32);

		// Execute the statement
		oci_execute($stmt);

		// Retrieve the output value
		$return_value = intval($result);

		// Close the statement and connection handles
		oci_free_statement($stmt);
		oci_close($conn);

		// Check the return value and handle errors if necessary
		if ($return_value > 0) {
			// Function was successful
			return true;
		} else {
			// Function returned an error
			return false;
		}
	}

}