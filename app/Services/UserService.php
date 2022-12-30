<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Infrastructure\BaseService;

class UserService extends BaseService implements UserServiceInterface
{
	/**
	 * UserService constructor.
	 *
	 * @param UserRepositoryInterface $repository
	 */
	public function __construct(UserRepositoryInterface $repository)
	{
		parent::__construct($repository);
	}
}