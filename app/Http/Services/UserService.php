<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\UserAuth;
use App\Models\UserContactInformation;
use App\Repositories\UserRepository;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use App\Mail\EmailConfirmation;
use App\Mail\ChangePasswordUrl;
use App\Mail\ChangeUserEmail;
use Exception;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PLSQL\GenericRepository;

use Illuminate\Support\Facades\Http;
use App\Http\Services\ErrorService;

class UserService
{

	private EntreRiosWSService $wsService;
	protected GenericRepository $genericRepository;
	private ErrorService $errorService;
	public function __construct(EntreRiosWSService $wsService, GenericRepository $genericRepository, ErrorService $errorService)
	{
		
		$this->wsService = $wsService;
		$this->genericRepository = $genericRepository;
		$this->errorService = $errorService;
	}

	public function ReCaptcha ($value)
	{
		$response = Http::get("https://www.google.com/recaptcha/api/siteverify",[
            'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
            'response' => $value
        ]);

		return $response->json()["success"];
	}


	public function getDniFromCuil($cuil){
		$dni = substr($cuil, 2, -1);

		if (str_starts_with($cuil, "0")) { $dni = substr($cuil, 1); }

		return $dni;
	}

	public function getCuilFromDNI($dni){

		$cuil = $this->genericRepository->getCuilFromDNI($dni);

		return $cuil;
	}

	public function createUser(array $request){
		
		$user = new User();
		$user->cuil = $request['cuil'];

		if ($request['prs_id'] != "NOTFOUND"){
			$user->prs_id = $request['prs_id'];
		}

		$user->name = $request['nombre'];
		$user->last_name = $request['apellido'];
		$user->email = $request['email'];
		$user->password = bcrypt($request['password']);
		$user->save();

		return $user;
	}

	public function getUser(string $cuil): User
	{
		$user = User::where('cuil', $cuil)->first();
		return $user;
	}

	public function saveUserValToken(int $userId, bool $actor): int
	{
		$code=1; 
		if($actor){
			$code = random_int(10000, 99999);
		}else{
			$code = random_int(1000, 9999);
		}

		$table_name = "USER_VALIDATION_TOKEN";
		$columns = "USER_ID, VAL_TOKEN, CREATED_AT";
		$values = $userId.','.$code.',sysdate';
		$result = $this->genericRepository->insertarFila($table_name, $columns, $values);
		if ($result){
			return $code;
		}else{
			return 0;
		}
	}

	public function sendEmail(User $user, $result_code, string $type)
	{
		// Obtén la hora en que se envió el último correo electrónico al destinatario
		$lastSentAt = Cache::get("last_email_sent_at_{$user->email}", 0);

		// Configura un intervalo de tiempo (en segundos) para el envío de correos electrónicos
		$interval = 300;

		// Verifica si ha pasado el intervalo de tiempo desde el último envío de correo electrónico
		if ( time() - $lastSentAt < $interval) {
			return response()->json([
				'status' => false,
				'message' => 'Email already sent. Wait ' . date("i:s", $interval - (time() - $lastSentAt)),
			], 400);
		}else{
			// Envía el correo electrónico

			if($type == "EmailVerification"){
				// Tipo de correo para verficiar email

				Mail::to($user->email)
				->queue((new EmailConfirmation($user, $result_code))
					->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
					->subject('Validación de correo e-mail'));

				// Actualiza la hora en que se envió el último correo electrónico al destinatario
				Cache::put("last_email_sent_at_{$user->email}", time(), 1440);

				return response()->json([
					'status' => true,
					'message' => 'Email sent',
					'email' => $user->email,
				], 201);

			}else{
				// Tipo de correo para restaurar password 

				Mail::to($user->email)
					->queue((new ChangePasswordUrl($user, $result_code))->from('ciudadanodigital@entrerios.gov.ar',
						'Ciudadano Digital - Provincia de Entre Ríos')->subject('Restaurar contraseña'));

				Cache::put("last_email_sent_at_{$user->email}", time(), 1440);

				return response()->json([
					'status' => true,
					'message' => 'Email sent',
				], 201);
			}		

		}
	}

	public function sendEmailForNewEmail(User $user, string $newEmail, $result_code){

		$lastSentAt = Cache::get("last_email_sent_at_{$user->email}", 0);

		// Configura un intervalo de tiempo (en segundos) para el envío de correos electrónicos
		$interval = 300;

		// Verifica si ha pasado el intervalo de tiempo desde el último envío de correo electrónico
		if ( time() - $lastSentAt < $interval) {
			return response()->json([
				'status' => false,
				'message' => 'Email already sent. Wait ' . date("i:s", $interval - (time() - $lastSentAt)),
			], 400);
		}else{

			Mail::to($newEmail)
			->queue((new ChangeUserEmail($user, $newEmail, $result_code))->from('ciudadanodigital@entrerios.gov.ar',
				'Ciudadano Digital - Provincia de Entre Ríos')->subject('Cambio de email'));

			Cache::put("last_email_sent_at_{$user->email}", time(), 1440);

			return response()->json([
				'status' => true,
				'message' => 'Email sent',
			], 201);
		}
		
	}



	public function signup(array $request)
	{
		try {
			if ($request['prs_id'] == "NOTFOUND" ){
				$user = self::createUser($request);

				$result_code = self::saveUserValToken($user->id, false);
				if ($result_code == 0) {
					$user->delete();

					return $this->errorService->genericError();
				}

				return self::sendEmail($user, $result_code, "EmailVerification" );
			} else {
				$dni = self::getDniFromCuil($request['cuil']);
				$rs = $this->wsService->checkUserCuil($dni);

				if ($rs->getData()->status === true) {
					// es una persona válida
					if ($rs->getData()->prs_id == $request['prs_id']){
	
						$user = self::createUser($request);
				     	//corroboro si el user es un actor

						$result_code = self::saveUserValToken($user->id, $rs->getData()->Actor);

						if ($result_code!=0){

							return self::sendEmail($user, $result_code, "EmailVerification" );
							
						}else{
							$user->delete();
							return $this->errorService->genericError();
						}
					}else{
						return $this->errorService->dataInconsistency();
					}
				} else {
					return $this->errorService->badCuil();
				}
			}
		} catch (Throwable $th) {
			throw new Exception("User creation failed ".$th, $th->getCode());
		}
	}

	public function setAuthType(User $user, string $auth_type, string $auth_level): bool
	{

		$column_name = "USER_ID";
		$column_value = $user->id;
		$table = "USER_AUTHENTICATION";
		$json = $this->genericRepository->getRow($table, $column_name, $column_value);

		if (empty($json)) {

			$column_name = "DESCRIPTION";
			$column_value = $auth_type;
			$table = "AUTHENTICATION_TYPES";
			$json_auth_types= $this->genericRepository->getRow($table, $column_name, $column_value);

			$table_name = "USER_AUTHENTICATION";
            $columns = "USER_ID, AUTHENTICATION_TYPES_ID, AUTH_LEVEL, CREATED_AT";
            $values = $user->id.','. $json_auth_types->ID .",'".$auth_level."', sysdate";
			$res= $this->genericRepository->insertarFila($table_name, $columns, $values);

			return $res;

		}else{

			$column_name = "DESCRIPTION";
			$column_value = $auth_type;
			$table = "AUTHENTICATION_TYPES";
			$json_auth_types= $this->genericRepository->getRow($table, $column_name, $column_value);

			$table_name= "USER_AUTHENTICATION";
			$columns= 'AUTHENTICATION_TYPES_ID = '.$json_auth_types->ID .", AUTH_LEVEL = '".$auth_level."' ,UPDATED_AT = sysdate";
			$values= 'USER_ID ='.$user->id;
			$res= $this->genericRepository->updateFila($table_name, $columns, $values);

			return $res;

		}

	}


	public function setUserContact(User $user, array $request): string
	{

		$column_name = "USER_ID";
		$column_value = $user->id;
		$table = "USER_CONTACT";
		$json_user_contact= $this->genericRepository->getRow($table, $column_name, $column_value);


		if(empty($json_user_contact)){

			$table_name = "USER_CONTACT";
			$columns = "USER_ID, BIRTHDAY, CELLPHONE_NUMBER, DEPARTMENT_ID, LOCALITY_ID, ADDRESS_STREET, ADDRESS_NUMBER, APARTMENT, CREATED_AT";
			$values = $user->id.",TO_DATE('".$request['birthday']."', 'DD/MM/YYYY'),'".$request['cellphone_number']."','".$request['department_id']."','".$request['locality_id']."','".$request['address_street']."','".$request['address_number']."','".$request['apartment']."',sysdate";
			$res= $this->genericRepository->insertarFila($table_name, $columns, $values);

			if($res){
				return "inserted";
			}else{
				return "DB internal problem";
			}
	
		}else{

			$table_name = "USER_CONTACT";		
			$columns = "BIRTHDAY = TO_DATE('".$request['birthday']."', 'DD/MM/YYYY'), CELLPHONE_NUMBER = '".$request['cellphone_number']."', DEPARTMENT_ID = '".$request['department_id']."', LOCALITY_ID = '".$request['locality_id']."', ADDRESS_STREET = '".$request['address_street']."', ADDRESS_NUMBER = '".$request['address_number']."', APARTMENT = '".$request['apartment']."', UPDATED_AT = SYSDATE";
			$values = "USER_ID = ".$user->id;

			$res= $this->genericRepository->updateFila($table_name, $columns, $values);

			//return $res;
			if($res){
				return "updated";
			}else{
				return "DB internal problem";
			}

		}
	}


}