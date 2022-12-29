<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Services\UserService;
use App\Mail\ChangePasswordUrl;
use App\Mail\EmailConfirmation;
use App\Models\User;
use App\Models\UserValidationToken;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
	public function checkUserCuil(Request $request, \Response $rs)
	{
		try {
			$validated = $this->validate($request, [
				'cuil' => 'required',
			]);

			$dni = substr($validated['cuil'], 2, -1);

			if (str_starts_with($validated['cuil'], "0")) {

				$dni = substr($validated['cuil'], 1);
			}

			$rs = $this->wsService->checkUserCuil($dni);

			return response()->json($rs);
		} catch (Throwable $e) {
			return \Response::json([
                'status' => false,
                'message' => $e->getMessage(),
                'details' => $e->__toString()
            ]);
		}
	}

	public function singup(CreateUserRequest $request): JsonResponse
	{

		try {
			$validated = $request->validated();

			$user = $this->userService->signup($validated);

			$code = random_int(1000, 9999);
			$validation_code = new UserValidationToken();
			$validation_code->user_id = $user->id;
			$validation_code->val_token = $code;
			//$validation_code->created_at = Carbon::now();
			$validation_code->save();

			Mail::to($user->email)
				#->cc('gvillanueva@entrerios.gov.ar')
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
	public function validate_new_user(Request $request): JsonResponse
	{
		$validated = $this->validate($request, [
			'cuil' => 'required',
			'confirmation_code' => 'required',
		]);

		$user = User::where('cuil', $validated['cuil'])->first();

        $validation_code = UserValidationToken::where('user_id' , $user->id )->first();

        if ( $validation_code->val_token == $validated['confirmation_code'] ){

			//$user->markEmailAsVerified();
			$user->email_verified_at = Carbon::now();
			$user->save();

			// Ademas eliminamos el codigo de confirmacion de la tabla user_confirmation_codes
			$validation_code->delete();

			return response()->json([
				'status' => true,
				'message' => 'Usuario confirmado',
				'token' => $user->createToken("user_token", ['nivel_1'])->accessToken
			]);

		} else {

			return response()->json([
				'status' => false,
				'message' => 'Codigo de confirmacion erroneo'
			], 400);

		}
	}

	/**
	 * @throws ValidationException
	 */
	public function login(Request $request): JsonResponse
	{

		$validated = $this->validate($request, [
			'cuil' => 'required',
			'password' => 'required',
		]);


		if (Auth::attempt($validated)) {

			$user = Auth::user();
			$token = $user->createToken('user_token', ['nivel_1'])->accessToken;

			//a solo modo informativo se envia que expira en 7 días. Tener en cuenta que la expiración del token se modifica en AuthServiceProvider
			$timestamp = now()->addDays(7);
			$expires_at = date('M d, Y H:i A', strtotime($timestamp));

			$user_data = [
				"name" => $user->nombre,
				"apellido" => $user->apellido
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
			return response()->json([
				'status' => false,
				'message' => 'Invalid Credentials',
			], 400);
		}

	}

	public function personal_data(Request $request): JsonResponse 
	{
		$validated = $this->validate($request, [
			'cuil' => 'required',
			'birthday"' => 'required',
            'cellphone_number' => 'required',
            'department_id' => 'required',
            'locality_id' => 'required',
            'address_street' => 'required',
            'address_number' => 'required',
            'apartment' => 'required',
		]);

		if (Auth::attempt($validated)) {
			
		}

	}

	public function test(): string
	{
		return ("llego");
	}


	/**
	 * @throws ValidationException
	 * @throws Exception
	 */
	public function password_reset_validation(Request $request): JsonResponse
	{

		$validated = $this->validate($request, [
			'cuil' => 'required',
		]);

		# aca no tengo que usar Auth porque eso funciona con la contraseña y aca no la tengo

		$user = User::where('cuil', $validated['cuil'])->first();

		$code = random_int(1000, 9999);

		$validation_code = UserValidationToken::where('id', $validated['cuil'])->first();

		if($validation_code){
            $validation_code->code = $code;
            $validation_code->created_at = Carbon::now()->timestamp;
            $validation_code->save();

            Mail::to($user->email)
            #->cc('gvillanueva@entrerios.gov.ar')
            ->queue((new ChangePasswordUrl($user , $code))->from('gvillanueva@entrerios.gov.ar', 'Portal Ciudadano - Provincia de Entre Ríos'));

            return response()->json([
                'status' => true,
                'message' => 'Correo enviado',
            ], 201);
        }else{
                $validation_code = new UserConfirmationCode();
                $validation_code->id = $validated['cuil'];
                $validation_code->code = $code;
                $validation_code->created_at = Carbon::now()->timestamp;
                $validation_code->save();

                Mail::to($user->email)
                #->cc('gvillanueva@entrerios.gov.ar')
                ->queue((new ChangePasswordUrl($user , $code))->from('gvillanueva@entrerios.gov.ar', 'Portal Ciudadano - Provincia de Entre Ríos'));

            return response()->json([
                'status' => true,
                'message' => 'Correo enviado',
            ], 201);
        }
	}

	/**
	 * @throws ValidationException
	 */
	public function password_reset(Request $request): JsonResponse
	{

		$validated = $this->validate($request, [
			'cuil' => 'required',
			'new_password' => 'required',
			'verification_code' => 'required',
		]);

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

		} else {

			return response()->json([
				'status' => false,
				'message' => 'Código de validación erroneo',
			], 201);
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
}
