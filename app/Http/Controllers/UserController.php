<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CheckUserCuilRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\passwordResetValidationRequest;
use App\Http\Requests\User\ResendEmailVerificacionRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\PersonalDataRequest;
use App\Http\Requests\User\ValidateNewUserRequest;
use App\Http\Requests\User\ChangeUserEmailValidationRequest;
use App\Http\Requests\User\ChangeUserEmailRequest;
use App\Http\Requests\User\ChangeNamesRequest;
use App\Http\Services\UserService;

use App\Http\Services\PlSqlService;

use App\Models\User;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Mail;
use Throwable;

class UserController extends Controller
{
	/**
	 * The user service implementation.
	 *
	 * @var UserService
	 */
	protected UserService $userService;
	private EntreRiosWSService $wsService;
	protected PlSqlService $plSqlServices;

	public function __construct(UserService $userService, PlSqlService $plSqlServices, EntreRiosWSService $wsService)
	{

		$this->userService = $userService;
		$this->plSqlServices = $plSqlServices;
		$this->wsService = $wsService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return JsonResponse
	 */
	public function index(): JsonResponse
	{
		//
		return response()->json();
	}

	/**
	 * @throws ValidationException
	 */
	public function checkUserCuil(CheckUserCuilRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$cuil = $validated['cuil'];
		$user = User::where('cuil', $cuil)->first();

		if ($user) {
			return response()->json([
				'status' => false,
				'message' => 'User already registered'
			], 409);

		} else {
			/*$dni = substr($validated['cuil'], 2, -1);
			if (str_starts_with($validated['cuil'], "0")) {
				$dni = substr($validated['cuil'], 1);
			}*/
			$dni= $this->userService->getDniFromCuil($validated['cuil']);
			$rs = $this->wsService->checkUserCuil($dni);
			return $rs;
		}
	}

	public function singup(CreateUserRequest $request): JsonResponse
	{

		try {

			$validated = $request->validated();
			$captcha=$this->userService->ReCaptcha($validated['captcha']); 

			if ($captcha){

				$user = User::where('cuil', $validated['cuil'])->first();

				if ($user) {

					return response()->json([
						'status' => false,
						'message' => 'User already registered'
					], 409);

				} else {

					$singupUserServices = $this->userService->signup($validated);

					return $singupUserServices;
				}

			}else{

				return response()->json([
					'status' => false,
					'message' => 'Bad captcha'
				], 403);

			}
			

		} catch (Throwable $th) {
			return response()->json([
				'status' => false,
				'message' => $th->getMessage()
			], 500);
		}

	}

	/**
	 * @throws ValidationException
	 */

	public function validateNewUser(ValidateNewUserRequest $request): JsonResponse
	{
		try {
			$validated = $request->validated();
			$aux = Crypt::decrypt($validated['token']);
			$cuil = explode('/', $aux)[0];
			$token = explode('/', $aux)[1];
			$user = User::where('cuil', $cuil)->first();

			if ($user) {

				$column_name = "USER_ID";
				$column_value = $user->id;
				$table = "USER_VALIDATION_TOKEN";
				$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

				try {

					if ($json->VAL_TOKEN == $token) {

						$user->email_verified_at = Carbon::now();
						$user->save();

						$resgitered=false;
						if($json->VAL_TOKEN > 9999) {
							$resgitered = $this->userService->setAuthType($user, "REGISTRADO", "level_4");
						}else{
							$resgitered = $this->userService->setAuthType($user, "REGISTRADO", "level_1");
						}

						if ($resgitered) {

							return response()->json([
								'status' => true,
								'message' => 'Email user confirmed',
							], 200);

						} else {

							return response()->json([
								'status' => false,
								'message' => 'Internal server problem, please try again later'
							], 503);
						}

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Bad confirmation code'
						], 400);
					}
				} catch (DecryptException $e) {
					return response()->json([
						'status' => false,
						'message' => 'Decryption error: '.$e->getMessage()
					], 400);
				}

			} else {
				return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);
			}
		} catch (Throwable $th) {

			return response()->json([
				'status' => false,
				'code' => $th->getCode(),
				'message' => $th->getMessage()
			], 500);
		}
	}

	/**
	 * @throws ValidationException
	 */
	public function login(LoginRequest $request): JsonResponse
	{
		try {

			$validated = $request->validated();
			$captcha=$this->userService->ReCaptcha($validated['captcha']); 

			if ($captcha){

				if (Auth::attempt($validated)) {

					$user = Auth::user();
	
					if ($user->email_verified_at == null) {
	
						return response()->json([
							'status' => false,
							'message' => 'email validation still pending'
						], 400);
	
					} else {
	
						$column_name = "USER_ID";
						$column_value = $user->id;
						$table = "USER_AUTHENTICATION";
						$user_auth = $this->plSqlServices->getRow($table, $column_name, $column_value);
	
	
						if (!empty($user_auth)) {
	
							$token = $user->createToken('user_token', [$user_auth->AUTH_LEVEL])->accessToken;
	
							//a solo modo informativo se envia que expira en 1 días. Tener en cuenta que la expiración del token se modifica en AuthServiceProvider
							$timestamp = now()->addDays(1);
							$expires_at = date('M d, Y H:i A', strtotime($timestamp));
	
							$column_name = "USER_ID";
							$column_value = $user->id;
							$table = "USER_CONTACT";
							$user_data = $this->plSqlServices->getRow($table, $column_name, $column_value);
	
							$user_data = [
								"user" => $user,
								"user_contact" => $user_data
							];
	
							return response()->json([
								'status' => true,
								'message' => 'Login successful',
								'access_token' => $token,
								'token_type' => 'bearer',
								'expires_at' => $expires_at,
								'user_data' => $user_data
							]);
	
						} else {
	
							//enviar error de nivel de autenticación
							return response()->json([
								'status' => false,
								'message' => 'Internal server problem, please try again later'
							], 503);
	
						}
					}
				} else {
	
					return response()->json([
						'status' => false,
						'message' => 'Invalid Credentials',
					], 400);
	
				}

			}else{

				return response()->json([
					'status' => false,
					'message' => 'Bad captcha'
				], 403);
	
			}
			


		} catch (Exception $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function contactPersonalData(PersonalDataRequest $request)
	{
		try {

			$validated = $request->validated();

			//obtener el cuil del access token que

			//$user = $this->userService->getUser($validated['cuil']);
			$user = Auth::guard('authentication')->user();

			if ($user) {

				$res_user_contact = $this->userService->setUserContact($user, $validated);

				if ($res_user_contact =="inserted") {

					$res_user_auth = $this->userService->setAuthType($user, "REGISTRADO", "level_2");

					if ($res_user_auth) {

						return response()->json([
							'status' => true,
							'message' => 'User contact data saved',
							'token' => $user->createToken("user_token", ['level_2'])->accessToken
						]);

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);

					}
				}else if($res_user_contact =="updated"){

					return response()->json([
						'status' => true,
						'message' => 'User contact data saved'
					]);

				} else {

					return response()->json([
						'status' => false,
						'message' => 'Internal server problem, please try again later'
					], 503);
				}
			} else {

				return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);

			}
		} catch (Throwable $th) {

			return response()->json([
				'status' => false,
				'message' => $th->getMessage()
			], 500);

		}
	}

	
	public function personalNames (ChangeNamesRequest $request): JsonResponse{

		$validated = $request->validated();

//		$user = $this->userService->getUser($validated['cuil']);
		$user = Auth::guard('authentication')->user();

		if($user){

			$column_name = "USER_ID";
			$column_value = $user->id;
     		$table = "USER_AUTHENTICATION";
			$json_auth_types = $this->plSqlServices->getRow($table, $column_name, $column_value);
			
			if($json_auth_types){

				if($json_auth_types->AUTH_LEVEL!="level_3"){

					$user->name = $request['name'];
					$user->last_name = $request['last_name'];
					$user->save();

					return response()->json([
						'status' => false,
						'message' => 'Data changed'
					], 200);

				}else{

					return response()->json([
						'status' => false,
						'message' => 'Not enabled to change the names'
					], 403);

				}

			}else{

				return response()->json([
					'status' => false,
					'message' => 'Internal server problem - Code:01'
				], 404);

			}

		}else{

			return response()->json([
				'status' => false,
				'message' => 'User not found'
			], 404);

		}

	}

	
	public function passwordResetValidation(passwordResetValidationRequest $request): JsonResponse
	{

		$validated = $request->validated();
		$captcha=$this->userService->ReCaptcha($validated['captcha']); 

		if ($captcha){

			$user = User::where('cuil', $validated['cuil'])->first();

			if ($user) {

				$code = random_int(1000, 9999);

				$column_name = "USER_ID";
				$column_value = $user->id;
				$table = "USER_VALIDATION_TOKEN";
				$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

				if (empty($json)) {

					$table_name = "USER_VALIDATION_TOKEN";
					$columns = "USER_ID, VAL_TOKEN, CREATED_AT";
					$values = $user->id.','.$code.',sysdate';

					$result = $this->plSqlServices->insertarFila($table_name, $columns, $values);

					if ($result) {

						return $this->userService->sendEmail($user, $code, "PasswordReset" );

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);

					}

				} else {

					$table_name = "USER_VALIDATION_TOKEN";
					$columns = 'VAL_TOKEN = '.$code.' ,UPDATED_AT = sysdate';
					$values = 'USER_ID ='.$user->id;
					$res = $this->plSqlServices->updateFila($table_name, $columns, $values);

					if ($res) {

						return $this->userService->sendEmail($user, $code, "PasswordReset" );

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);
					}

				}

			}else {

				return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);

			}

		}else{

			return response()->json([
				'status' => false,
				'message' => 'Bad captcha'
			], 403);

		}
		
		

	}

	/**
	 * @throws ValidationException
	 */
	public function passwordReset(PasswordResetRequest $request): JsonResponse
	{

		try {
			$validated = $request->validated();
			$aux = Crypt::decrypt($validated['token']);
			$cuil = explode('/', $aux)[0];
			$token = explode('/', $aux)[1];
			$user = User::where('cuil', $cuil)->first();
			
			$column_name = "USER_ID";
			$column_value = $user->id;
			$table = "USER_VALIDATION_TOKEN";
			$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

			if (!empty($json)) {

				if ($json->VAL_TOKEN == $token) {

					$user->password = bcrypt($validated['new_password']);
					$user->save();

					return response()->json([
						'status' => true,
						'message' => 'Password changed',
					], 200);

				} else {

					return response()->json([
						'status' => false,
						'message' => 'Bad validation code',
					], 201);

				}

			} else {

				return response()->json([
					'status' => false,
					'message' => 'Internal server problem, please try again later'
				], 503);

			}

		}catch (DecryptException $e) {
			return response()->json([
				'status' => false,
				'message' => 'Invalid token',
			], 400);
		}

		
	}

	public function resendEmailVerificacion(ResendEmailVerificacionRequest $request): JsonResponse
	{
		$validated = $request->validated();
		
		$captcha=$this->userService->ReCaptcha($validated['captcha']); 

		if($captcha){

			$cuil = $validated['cuil'];
			$user = User::where('cuil', $validated['cuil'])->first();

			if($user){

				if ($user->email_verified_at==null){

					$code = random_int(1000, 9999);

					$column_name = "USER_ID";
					$column_value = $user->id;
					$table = "USER_VALIDATION_TOKEN";
					$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

					if($json->VAL_TOKEN>9999){
						$code = random_int(10000, 99999);
					}else{
						$code = random_int(1000, 9999);
					}

					$table_name = "USER_VALIDATION_TOKEN";
					$columns = 'VAL_TOKEN = '.$code.' ,UPDATED_AT = sysdate';
					$values = 'USER_ID ='.$user->id;
					$result = $this->plSqlServices->updateFila($table_name, $columns, $values);

					if ($result){

						return $this->userService->sendEmail($user, $code, "EmailVerification" );

					}else{

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);
					}

				}else{

					return response()->json([
						'status' => false,
						'message' => 'Email alredy verified'
					], 401);
				}

			}else{

				return response()->json([
					'status' => false,
					'message' => 'Invalid Cuil'
				], 503);

			}

		}else{

			return response()->json([
				'status' => false,
				'message' => 'Bad captcha'
			], 403);

		}

		

	}

	public function changeNewEmailValidation (ChangeUserEmailValidationRequest $request): JsonResponse
	{

		$validated = $request->validated();

		#$user = User::where('cuil', $validated['cuil'])->first();
		$user = Auth::guard('authentication')->user();

		if ($user) {

			$code = random_int(1000, 9999);

			$column_name = "USER_ID";
			$column_value = $user->id;
			$table = "USER_VALIDATION_TOKEN";
			$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

			if (empty($json)) {

				$table_name = "USER_VALIDATION_TOKEN";
				$columns = "USER_ID, VAL_TOKEN, CREATED_AT";
				$values = $user->id.','.$code.',sysdate';

				$result = $this->plSqlServices->insertarFila($table_name, $columns, $values);

				if ($result) {

					return $this->userService->sendEmailForNewEmail($user,$validated['new_email'], $code );

				} else {

					return response()->json([
						'status' => false,
						'message' => 'Internal server problem, please try again later'
					], 503);

				}

			} else {

				$table_name = "USER_VALIDATION_TOKEN";
				$columns = 'VAL_TOKEN = '.$code.' ,UPDATED_AT = sysdate';
				$values = 'USER_ID ='.$user->id;
				$res = $this->plSqlServices->updateFila($table_name, $columns, $values);

				if ($res) {

					return $this->userService->sendEmailForNewEmail($user,$validated['new_email'], $code );

				} else {

					return response()->json([
						'status' => false,
						'message' => 'Internal server problem, please try again later'
					], 503);
				}

			}

			}else {

				return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);

			}
		
	}

	public function changeEmail(ChangeUserEmailRequest $request): JsonResponse
	{
		try{

			$validated = $request->validated();
			$aux = Crypt::decrypt($validated['token']);
			$cuil = explode('/', $aux)[0];
			$new_email = explode('/', $aux)[1];
			$token = explode('/', $aux)[2];
			
			#$user = User::where('cuil', $cuil)->first();
			$user = Auth::guard('authentication')->user();

			if ($user && $user->cuil==$cuil){

				$column_name = "USER_ID";
				$column_value = $user->id;
				$table = "USER_VALIDATION_TOKEN";
				$json = $this->plSqlServices->getRow($table, $column_name, $column_value);

				if (!empty($json)) {

					if ($json->VAL_TOKEN == $token) {

						$user->email = $new_email;
						$user->save();

						return response()->json([
							'status' => true,
							'message' => 'Email changed',
						], 201);

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Bad validation code',
						], 201);

					}

			} else {

				return response()->json([
					'status' => false,
					'message' => 'Internal server problem, please try again later'
				], 503);

			}

			}else{

				return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);

			}

			

		}catch (DecryptException $e) {
			return response()->json([
				'status' => false,
				'message' => 'Invalid token',
			], 400);
		}

		
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function show(string $id): JsonResponse
	{
		return response()->json(["id" => $id]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function update(string $id): JsonResponse
	{
		return response()->json(["id" => $id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $id
	 * @return void
	 */
	public function destroy(string $id): void
	{
		//
	}

	public function eliminarUser(CheckUserCuilRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$cuil = $validated['cuil'];
		$user = User::where('cuil', $cuil)->first();

		if ($user) {
			$user->delete();

			return response()->json([
				'status' => true,
				'message' => 'User removed'
			], 201);
		} else {
			return response()->json([
				'status' => false,
				'message' => 'there is not that user'
			], 409);
		}
	}
}
