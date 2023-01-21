<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\UserAuth;
use App\Models\UserContactInformation;
use App\Repositories\UserRepository;
use Exception;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserService
{

	/**
	 * @param  UserRepository  $userRepository
	 */
	public function __construct(
		private readonly UserRepository $userRepository,
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function signup(array $request): User
	{
		try {

			$user = new User();
			$user->cuil = $request['cuil'];
			$user->prs_id = $request['prs_id'];
			$user->name = $request['nombre'];
			$user->last_name = $request['apellido'];
			$user->email = $request['email'];
			$user->password = bcrypt($request['password']);
			$user->save();
			return $user;

		} catch (Throwable $th) {
			throw new Exception("User creation failed ".$th, $th->getCode());
		}
	}

	public function getUser(string $cuil): User
	{
		$user = User::where('cuil', $cuil)->first();
		return $user;

	}

	public function setAuthType(User $user, string $auth_type, string $auth_level): bool
	{


		$column_name = "USER_ID";
		$column_value = $user->id;
		$table = "USER_AUTHENTICATION";
		$json = self::getRow($table, $column_name, $column_value);

		if (empty($json)) {

			$column_name = "DESCRIPTION";
			$column_value = $auth_type;
			$table = "AUTHENTICATION_TYPES";
			$json_auth_types= self::getRow($table, $column_name, $column_value);
			
			$table_name = "USER_AUTHENTICATION";
            $columns = "USER_ID, AUTHENTICATION_TYPES_ID, AUTH_LEVEL, CREATED_AT";
            $values = $user->id.','. $json_auth_types->ID .','.$auth_level.',sysdate';
			$res= self::insertarFila($table_name, $columns, $values);
			dd($res);

			return $res;

		}else{

			$column_name = "DESCRIPTION";
			$column_value = $auth_type;
			$table = "AUTHENTICATION_TYPES";
			$json_auth_types= self::getRow($table, $column_name, $column_value);

			$table_name= "USER_AUTHENTICATION";
			$columns= 'AUTHENTICATION_TYPES_ID = '.$json_auth_types->id .', AUTH_LEVEL = '.$auth_level.' ,UPDATED_AT = sysdate';
			$values= 'USER_ID ='.$user->id;
			$res= self::updateFila($table_name, $columns, $values);
			return $res;
		}



	}

	public function setUserContact(User $user, array $request): bool
	{


		$fecha = explode("/", $request['birthday']);

		$table_name = "USER_CONTACT";
		$columns = "USER_ID, BIRTHDAY, CELLPHONE_NUMBER, DEPARTMENT_ID, LOCALITY_ID, ADDRESS_STREET, ADDRESS_NUMBER, APARTMENT, CREATED_AT";
		$values = $user->id.",'". $fecha[0]."-".$fecha[1]."-".$fecha[2]."','".$request['cellphone_number']."','".$request['department_id']."','".$request['locality_id']."','".$request['address_street']."','".$request['address_number']."','".$request['apartment']."',sysdate";
		$res= self::insertarFila($table_name, $columns, $values);

		return $res;

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

	/*public function getFilaUserValidationToken(string $column_name, string $column_value): mixed{

			$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_USER_VALIDATION_TOKEN(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);
			$json = json_decode($result[0]->result);

			return $json;
	}

	public function getFilaUserContact(string $column_name, string $column_value): mixed{

		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_USER_CONTACT(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);
		$json = json_decode($result[0]->result);

		return $json;
	}

	public function getFilaUserAutentication(string $column_name, string $column_value): mixed{

		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_USER_AUTHENTICATION(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);
		$json = json_decode($result[0]->result);

		return $json;
	}

	public function getFilaAutenticationType(string $column_name, string $column_value): mixed{

		$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_AUTHENTICATION_TYPES(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);
		$json = json_decode($result[0]->result);

		return $json;
	}*/

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

