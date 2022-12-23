<?php

namespace App\Providers;

use App\Services\Contracts\BaseServiceInterface;
use App\Services\Infrastructure\BaseService;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			BaseServiceInterface::class,
			BaseService::class
		);

		$this->app->bind(
			\App\Services\Contracts\UserServiceInterface::class,
			\App\Services\UserService::class
		);
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
