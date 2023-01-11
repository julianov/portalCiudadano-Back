<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;
use Laravel\Passport\Http\Middleware\CheckScopes;

class Kernel extends HttpKernel
{
<<<<<<< HEAD
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
      
    ];
=======
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * These middleware are run during every request to your application.
	 *
	 * @var array<int, class-string|string>
	 */
	protected $middleware = [
		// \App\Http\Middleware\TrustHosts::class,
		TrustProxies::class,
		HandleCors::class,
		PreventRequestsDuringMaintenance::class,
		ValidatePostSize::class,
		TrimStrings::class,
		ConvertEmptyStringsToNull::class,
>>>>>>> e40bfe757f261588605a6116f2891d17defade28

	];

	/**
	 * The application's route middleware groups.
	 *
	 * @var array<string, array<int, class-string|string>>
	 */
	protected $middlewareGroups = [
		'web' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
		],

<<<<<<< HEAD
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
    ];
=======
		'api' => [
			// \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
			'throttle:api',
			SubstituteBindings::class,
		],
	];

	/**
	 * The application's route middleware.
	 *
	 * These middleware may be assigned to groups or used individually.
	 *
	 * @var array<string, class-string|string>
	 */
	protected $routeMiddleware = [
		'auth' => Authenticate::class,
		'auth.basic' => AuthenticateWithBasicAuth::class,
		'auth.session' => AuthenticateSession::class,
		'cache.headers' => SetCacheHeaders::class,
		'can' => Authorize::class,
		'guest' => RedirectIfAuthenticated::class,
		'password.confirm' => RequirePassword::class,
		'signed' => ValidateSignature::class,
		'throttle' => ThrottleRequests::class,
		'verified' => EnsureEmailIsVerified::class,
		'scopes' => CheckScopes::class,
		'scope' => CheckForAnyScope::class,
	];
>>>>>>> e40bfe757f261588605a6116f2891d17defade28
}
