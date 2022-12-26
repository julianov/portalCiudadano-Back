<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
			UserRepositoryInterface::class,
			BaseRepository::class
		);
		$this->app->bind(
			\App\Repositories\Contracts\UserRepositoryInterface::class,
			UserRepository::class
		);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
