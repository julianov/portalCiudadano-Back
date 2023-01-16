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
use App\Models\UserContactInformation;
use App\Models\UserValidationToken;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
		}

		$dni = substr($validated['cuil'], 2, -1);

		if (str_starts_with($validated['cuil'], "0")) {
			$dni = substr($validated['cuil'], 1);
		}

		$rs = $this->wsService->checkUserCuil($dni);
		return response()->json($rs);
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

			}
			$user = $this->userService->signup($validated);


			$code = random_int(1000, 9999);

			$table_name = "USER_VALIDATION_TOKEN";
            $columns = "USER_ID, VAL_TOKEN, CREATED_AT";
            $values = $user->id.','.$code.','.Carbon::now();
			$result=false;

			$res = DB::select("DECLARE l_result BOOLEAN; BEGIN l_result := CIUD_UTILIDADES_PKG.INSERTAR_FILA(:table_name, :columns, :values); END;",
            [
                'table_name' => $table_name,
                'columns' => $columns,
                'values' => $values,
            ]);


			/*

			$validation_code = new UserValidationToken();
			$validation_code->user_id = $user->id;
			$validation_code->val_token = $code;
			//$validation_code->created_at = Carbon::now();
			$validation_code->save();
*/
			Mail::to($user->email)
				->queue((new EmailConfirmation($user, $code))->from('gvillanueva@entrerios.gov.ar',
					'Portal Ciudadano - Provincia de Entre Ríos'));

			return response()->json([
				'status' => true,
				'message' => 'Email sent',
			], 201);

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
			//$this->userService->
			$validated = $request->validated();
			$user = User::where('cuil', $validated['cuil'])->first();

			$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_USER_VALIDATION_TOKEN(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);


			//$validation_code = UserValidationToken::where('user_id', $user->id)->first();
			$column_name = "USER_ID";
			$column_value = 3;
			$result = DB::select("SELECT CIUD_UTILIDADES_PKG.FILA_USER_VALIDATION_TOKEN(:column_name,:column_value) as result FROM DUAL", ['column_name' => $column_name, 'column_value' => $column_value]);

			$json = json_decode($result[0]->result);

			if ($json->val_token == $validated['confirmation_code']) {
				$user->email_verified_at = Carbon::now();
				$user->save();
				$this->userService->setAuthType($user, "REGISTRADO", "level_1");

				return response()->json([
					'status' => true,
					'message' => 'Email user confirmed',
					'token' => $user->createToken("user_token", ['level_1'])->accessToken
				]);

			}
			return response()->json([
				'status' => false,
				'message' => 'Bad confirmation code'
			], 400);
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


				} else {
					$token = $user->createToken('user_token', ['level_1'])->accessToken;

					//a solo modo informativo se envia que expira en 7 días. Tener en cuenta que la expiración del token se modifica en AuthServiceProvider
					$timestamp = now()->addDays(7);
					$expires_at = date('M d, Y H:i A', strtotime($timestamp));

					$user_data = [
						"user" => $user,
						"user_contact" => UserContactInformation::where('user_id', $user->id)->first()
					];

					return response()->json([
						'status' => true,
						'message' => 'Login successful',
						'access_token' => $token,
						'token_type' => 'bearer',
						'expires_at' => $expires_at,
						'user_data' => $user_data
					]);
				}
			}
			return response()->json([
				'status' => false,
				'message' => 'Invalid Credentials',
			], 400);
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
			$this->userService->setUserContact($user, $validated);
			$this->userService->setAuthType($user, "REGISTRADO", "level_2");

			return response()->json([
				'status' => true,
				'message' => 'User contact data saved',
				'token' => $user->createToken("user_token", ['level_2'])->accessToken
			]);
		} catch (Throwable $th) {
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

		# aca no tengo que usar Auth porque eso funciona con la contraseña y aca no la tengo

		$user = User::where('cuil', $validated['cuil'])->first();

		$code = random_int(1000, 9999);

		$validation_code = UserValidationToken::where('id', $validated['cuil'])->first();

		if (!$validation_code) {
			$validation_code = new UserConfirmationCode();
			$validation_code->id = $validated['cuil'];

		}
		$validation_code->code = $code;
		$validation_code->created_at = Carbon::now()->timestamp;
		$validation_code->save();
		Mail::to($user->email)
			#->cc('gvillanueva@entrerios.gov.ar')
			->queue((new ChangePasswordUrl($user, $code))->from('gvillanueva@entrerios.gov.ar',
				'Portal Ciudadano - Provincia de Entre Ríos'));
		return response()->json([
			'status' => true,
			'message' => 'Correo enviado',
		], 201);
	}

	/**
	 * @throws ValidationException
	 */
	public function passwordReset(PasswordResetRequest $request): JsonResponse
	{

		$validated = $request->validated();

		$validation_code = UserConfirmationCode::where('id', $validated['cuil'])->first();

		if ($validation_code == $validated['verification_code']) {

			$user = User::where('cuil', $validated['cuil'])->first();
			$user->password = bcrypt($validated['new_password']);
			$user->save();
			$validation_code->delete();

			return response()->json([
				'status' => true,
				'message' => 'Contraseña cambiada',
			], 201);

		}
		return response()->json([
			'status' => false,
			'message' => 'Código de validación erroneo',
		], 201);
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
}
