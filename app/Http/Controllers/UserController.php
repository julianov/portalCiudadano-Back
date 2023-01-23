<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CheckUserCuilRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\PersonalDataRequest;
use App\Http\Requests\User\ValidateNewUserRequest;
use App\Http\Services\UserService;
use App\Mail\ChangePasswordUrl;
use App\Mail\EmailConfirmation;
use App\Models\User;
use App\Models\UserValidationToken;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Mail;
use Throwable;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
	/**
	 * The user service implementation.
	 *
	 * @var UserService
	 */
	protected UserService $userService;
	private EntreRiosWSService $wsService;

	public function __construct(UserService $userService, EntreRiosWSService $wsService)
	{

		$this->userService = $userService;
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

		}else{

			$dni = substr($validated['cuil'], 2, -1);

			if (str_starts_with($validated['cuil'], "0")) {
				$dni = substr($validated['cuil'], 1);
			}

			$rs = $this->wsService->checkUserCuil($dni);

			return response()->json($rs);

		}

		
	}

	public function singup(CreateUserRequest $request): JsonResponse
	{

		try {

			$validated = $request->validated();
			$user = User::where('cuil', $validated['cuil'])->first();

			if ($user) {

				return response()->json([
					'status' => false,
					'message' => 'User already registered'
				], 409);

			}else{

				$user = $this->userService->signup($validated);

				$code = random_int(1000, 9999);

				$table_name = "USER_VALIDATION_TOKEN";
				$columns = "USER_ID, VAL_TOKEN, CREATED_AT";
				$values = $user->id.','.$code.',sysdate';
				$result = $this->userService->insertarFila($table_name, $columns, $values);

				if ($result){

					Mail::to($user->email)
					->queue((new EmailConfirmation($user, $code))->from('ciudadanodigital@entrerios.gov.ar',
						'Ciudadano Digital - Provincia de Entre Ríos')->subject('Validación de correo e-mail'));
						
					return response()->json([
						'status' => true,
						'message' => 'Email sent',
					], 201);

				}else{

					$user->delete();

					return response()->json([
						'status' => false,
						'message' => 'Internal server problem, please try again later'
					], 503);
				}

				
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
			$user = User::where('cuil', $validated['cuil'])->first();

			if($user){

				$column_name = "USER_ID";
				$column_value = $user->id;
				$table="USER_VALIDATION_TOKEN";
				$json = $this->userService->getRow($table, $column_name, $column_value);
				try {
					$original_code= Crypt::decrypt($json->VAL_TOKEN);
					if ($original_code == $validated['confirmation_code']) {
						$user->email_verified_at = Carbon::now();
						$user->save();
						$resgitered = $this->userService->setAuthType($user, "REGISTRADO", "level_1");
					if($resgitered){

							return response()->json([
								'status' => true,
								'message' => 'Email user confirmed',
							], 200);

						}else{

							return response()->json([
								'status' => false,
								'message' => 'Internal server problem, please try again later'
							], 503);
				
						}
						
					}else{
						
						return response()->json([
							'status' => false,
							'message' => 'Bad confirmation code'
						], 400);
					}
				} catch (DecryptException $e) {
					return response()->json([
						'status' => false,
						'message' => 'Decryption error: ' . $e->getMessage()
					], 400);
				}

			}else{

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

			if (Auth::attempt($validated)) {

				$user = Auth::user();

				if ($user->email_verified_at == null) {

					return response()->json([
						'status' => false,
						'message' => 'email validation still pending'
					], 400);

				}else{

					$column_name = "USER_ID";
					$column_value = $user->id;
					$table="USER_AUTHENTICATION";
					$user_auth = $this->userService->getRow($table,$column_name, $column_value);


					if (!empty($user_auth)){

						$token = $user->createToken('user_token', [$user_auth->AUTH_LEVEL])->accessToken;

						//a solo modo informativo se envia que expira en 1 días. Tener en cuenta que la expiración del token se modifica en AuthServiceProvider
						$timestamp = now()->addDays(1);
						$expires_at = date('M d, Y H:i A', strtotime($timestamp));
	
						$column_name = "USER_ID";
						$column_value = $user->id;
						$table="USER_CONTACT";
						$user_data = $this->userService->getRow($table,$column_name, $column_value);
	
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

					}else{

						//enviar error de nivel de autenticación
						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);

					}
				}
			}else{

				return response()->json([
					'status' => false,
					'message' => 'Invalid Credentials',
				], 400);

			}
			

		} catch (Exception $e) {
			return response()->json([
				'status' => false,
				'message' => $e->getMessage()
			], 500);
		}
	}

	public function personalData(PersonalDataRequest $request)
	{
		try {

			$validated = $request->validated();

			$user = $this->userService->getUser($validated['cuil']);
			
			if ($user){

				$res_user_contact = $this->userService->setUserContact($user, $validated);
				
				if ($res_user_contact){

					$res_user_auth = $this->userService->setAuthType($user, "REGISTRADO", "level_2");
					
					if($res_user_auth){

						return response()->json([
							'status' => true,
							'message' => 'User contact data saved',
							'token' => $user->createToken("user_token", ['level_2'])->accessToken
						]);

					}else{

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);

					}
				}else{

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
		}catch (Throwable $th) {

			return response()->json([
				'status' => false,
				'message' => $th->getMessage()
			], 500);

		}
	}

	/**
	 * @throws ValidationException
	 * @throws Exception
	 */
	public function passwordResetValidation(CheckUserCuilRequest $request): JsonResponse
	{

		$validated = $this->validate($request, [
			'cuil' => 'required',
		]);

		$user = User::where('cuil', $validated['cuil'])->first();

		$code = random_int(1000, 9999);

        $column_name = "USER_ID";
		$column_value = $user->id;
		$table="USER_VALIDATION_TOKEN";
		$json = $this->userService->getRow($table, $column_name, $column_value);

        if (empty($json)){

            $table_name = "USER_VALIDATION_TOKEN";
            $columns = "USER_ID, VAL_TOKEN, CREATED_AT";
            $values = $user->id.','.$code.',sysdate';

			$result= $this->userService->insertarFila($table_name, $columns, $values);

            if ($result){

                Mail::to($user->email)
                ->queue((new ChangePasswordUrl($user, $code))->from('ciudadanodigital@entrerios.gov.ar',
                    'Ciudadano Digital - Provincia de Entre Ríos')->subject('Restaurar contraseña'));

				return response()->json([
					'status' => true,
					'message' => 'Email sent',
				], 201);

            }else{

                return response()->json([
					'status' => false,
					'message' => 'Internal server problem, please try again later'
				], 503);
                
            }

        }else{

            $table_name= "USER_VALIDATION_TOKEN";
			$columns= 'VAL_TOKEN = '.$code.' ,UPDATED_AT = sysdate';
			$values= 'USER_ID ='.$user->id;
			$res= $this->userService->updateFila($table_name, $columns, $values);

            if ($res){

                Mail::to($user->email)
                ->queue((new ChangePasswordUrl($user, $code))->from('ciudadanodigital@entrerios.gov.ar',
                    'Ciudadano Digital - Provincia de Entre Ríos')->subject('Restaurar contraseña'));
    
				return response()->json([
					'status' => true,
					'message' => 'Email sent',
				], 201);

            }else{

                return response()->json([
					'status' => false,
					'message' => 'Internal server problem, please try again later'
				], 503);
            }

        }

	}

	/**
	 * @throws ValidationException
	 */
	public function passwordReset(PasswordResetRequest $request): JsonResponse
	{

		$validated = $request->validated();

        $column_name = "USER_ID";
		$column_value = $user->id;
		$table="USER_VALIDATION_TOKEN";
		$json = $this->userService->getRow($table, $column_name, $column_value);

		if (!empty($json)){

			if ($json->VAL_TOKEN == $validated['verification_code']) {

				$user = User::where('cuil', $validated['cuil'])->first();
				$user->password = bcrypt($validated['new_password']);
				$user->save();
	
				return response()->json([
					'status' => true,
					'message' => 'Password changed',
				], 201);
	
			}else{
				
				return response()->json([
					'status' => false,
					'message' => 'Bad validation code',
				], 201);
	
			}

		}else{

			return response()->json([
				'status' => false,
				'message' => 'Internal server problem, please try again later'
			], 503);

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

    public function eliminarUser(CheckUserCuilRequest $request): JsonResponse{
    		$validated = $request->validated();
    		$cuil = $validated['cuil'];
    		$user = User::where('cuil', $cuil)->first();

    		if ($user) {
    				$user->delete();

    				return response()->json([
    						'status' => true,
    						'message' => 'User removed'
    				], 201);
    		}
    		else{
    				return response()->json([
    						'status' => false,
    						'message' => 'there is not that user'
    				], 409);
    		}
    	}
}
