<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Eloquent\BaseRepository implements UserRepositoryInterface
{
	protected Model $model;

	public function __construct(User $model)
	{
		parent::__construct($model);
	}
}