<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',


	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		//Tiempo de expiración de los tokens: 7 días
		Passport::tokensExpireIn(now()->addDays(7));
		Passport::personalAccessTokensExpireIn(now()->addDays(7));

		//Niveles de autenticación de usuarios
		Passport::tokensCan([
			'nivel_1' => 'nivel de acceso básico',
			'nivel_2' => 'nivel de validación de identidad por aplicación',
			'nivel_3' => 'nivel de validación de identidad presencial',
		]);


	}
}
