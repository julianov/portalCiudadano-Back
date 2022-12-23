<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserConfirmationCode
 * @property string $id
 * @property string $code
 * @property string $type
 * @property string $created_at
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
