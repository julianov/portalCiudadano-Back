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

		//Tiempo de expiración de los tokens: 1 día
		Passport::tokensExpireIn(now()->addDays(1));
		Passport::personalAccessTokensExpireIn(now()->addDays(1));

		//Niveles de autenticación de usuarios
		Passport::tokensCan([
			'level_1' => 'nivel de acceso básico',
			'level_2' => 'nivel de validación de identidad por datos personales',
			'level_3' => 'nivel de validación de identidad por aplicación',
			'level_4' => 'nivel de validación de identidad de actor',
		]);


	}
}
