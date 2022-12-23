<?php

namespace App\Http\Controllers;

use App\Mail\ChangePasswordUrl;
use App\Mail\EmailConfirmation;
use App\Models\User;
use App\Models\UserConfirmationCode;
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
	public function check_user_cuil(Request $request): JsonResponse
	{

		$validated = $this->validate($request, [
			'cuil' => 'required',
		]);

		$dni = substr($validated['cuil'], 2, -1);

		if (str_starts_with($validated['cuil'], "0")) {

			$dni = substr($validated['cuil'], 1);
		}

		$response_user = Http::withHeaders(
			['Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoid3NVVE4iLCJpYXQiOjE2NzE2Mzc1NjAsImV4cCI6MTcwMzE3MzU2MCwic2lzdGVtYSI6IjIyIn0.7Ta6rtdsURlo2ccUk15WpYd5tX60If2mBcpsr2Kx5_o'])->get("https://apps.entrerios.gov.ar/wsEntreRios/consultaPF/".$dni);

		$url_actor = "https://apps.entrerios.gov.ar/wsEntreRios/consultaBduActorEntidad/".$dni."/".json_decode($response_user->body(),
				true)[0]["SEXO"];

		$response_actor = Http::withHeaders([
			'Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoid3NVVE4iLCJpYXQiOjE2NzE2Mzc1NjAsImV4cCI6MTcwMzE3MzU2MCwic2lzdGVtYSI6IjIyIn0.7Ta6rtdsURlo2ccUk15WpYd5tX60If2mBcpsr2Kx5_o',
		])->get($url_actor);

		return response()->json([
			'status' => true,
			'user' => $response_user->body(),
			'actor' => $response_actor->body(),
		], 201);


	}

	public function singup(Request $request): JsonResponse
	{

		try {
			$validated = $this->validate($request, [
				'cuil' => 'required',
				'nombre' => 'required',
				'apellido' => 'required',
				'email' => 'required',
				'password' => 'required',
			]);

			$user = new User();
			$user->cuil = $validated['cuil'];
			$user->nombre = $validated['nombre'];
			$user->apellido = $validated['apellido'];
			$user->email = $validated['email'];

			$user->password = bcrypt($validated['password']);
			//  $user->confirmation_code=$bignum = hexdec( md5("test") );

			$user->save();

			$code = random_int(1000, 9999);
			$validation_code = new UserConfirmationCode();
			$validation_code->id = $user->cuil;
			$validation_code->code = $code;
			$validation_code->created_at = Carbon::now()->timestamp;
			$validation_code->save();

			Mail::to($user->email)
				#->cc('gvillanueva@entrerios.gov.ar')
				->queue((new EmailConfirmation($user, $code))->from('gvillanueva@entrerios.gov.ar',
					'Portal Ciudadano - Provincia de Entre Ríos'));

			return response()->json([
				'status' => true,
				'message' => 'Correo enviado',
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

		$validation_code = UserConfirmationCode::where('id', $user->cuil)->first();

		if ($validation_code->code == $validated['confirmation_code']) {

			$user->markEmailAsVerified();
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

		$validation_code = UserConfirmationCode::where('id', $validated['cuil'])->first();

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
		return response()->json(["id"=>$id]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return JsonResponse
	 */
	public function update(int $id): JsonResponse
	{
		return response()->json(["id"=>$id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return void
	 */
	public function destroy(int $id): void
	{
		//
	}
}
