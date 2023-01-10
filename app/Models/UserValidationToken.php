<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $user_id Identificador del usuario
 * @property string $val_token Token de validación
 * @mixin Eloquent
 */
class UserValidationToken extends Model
{
	use HasFactory;

	public $timestamps = false;

	protected $table = "user_validation_token";

	protected $fillable = [
		'id',
		'user_id',
		'val_token',
	];

	protected $casts = [
		'created_at' => 'datetime',
	];
}
