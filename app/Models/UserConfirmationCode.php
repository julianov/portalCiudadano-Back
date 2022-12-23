<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserConfirmationCode
 * @property string $code
 * @property string $user_id
 * @property string $type
 * @property string $status
 * @property string $expiration_date
 * @mixin Eloquent
 */
class UserConfirmationCode extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $fillable = [
		'id',
		'code',
	];

	protected $casts = [
		'created_at' => 'datetime',
	];
}
