<?php

namespace App\Http\Services;
use App\Models\User;
use Exception;
use Throwable;

class UserService {

	/**
	 * @throws Exception
	 */
	public function signup(array $request): User
	{
		try {
			$user = new User();
			$user->cuil = $request['cuil'];
			$user->name = $request['nombre'];
			$user->last_name = $request['apellido'];

			#$user->email = $request['email'];

			$user->password = bcrypt($request['password']);

			$user->save();

			return $user;
		} catch (Throwable $th) {
			throw new Exception("Error al crear el usuario", $th->getCode());
		}
	}
}