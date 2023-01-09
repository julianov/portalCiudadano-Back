<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\UserAuth;
use App\Models\UserContactInformation;
use Exception;
use Throwable;

class UserService
{

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

	public function setAuthType(User $user, string $auth_type, string $auth_level): UserAuth
	{


		//resolver error de authentication_types_id tabla vacia 

		$user_auth_type = UserAuth::where('user_id', $user->id)->first();

		if (!$user_auth_type) {
			$user_auth_type = new UserAuth();
			$user_auth_type->user_id = $user->id;
			//no hay nada en la tabla authentication_types

		}
		$user_auth_type->authentication_types_id = 1;
		$user_auth_type->auth_level = $auth_level;
		$user_auth_type->save();
		return $user_auth_type;

	}

	public function setUserContact(User $user, array $request): UserContactInformation
	{

		//dd($request['birthday']);

		$fecha = explode("/", $request['birthday']);

		$user_contact = new UserContactInformation();
		$user_contact->user_id = $user->id;
		$user_contact->birthday = $fecha[2]."/".$fecha[1]."/".$fecha[0];
		$user_contact->cellphone_number = $request['cellphone_number'];
		$user_contact->department_id = $request['department_id'];
		$user_contact->locality_id = $request['locality_id'];
		$user_contact->address_street = $request['address_street'];
		$user_contact->address_number = $request['address_number'];
		$user_contact->apartment = $request['apartment'];

		$user_contact->save();

		return $user_contact;

	}
}