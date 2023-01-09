<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Presential
 * @property string $dni_front
 * @property string $dni_back
 * @property string $user_photo
 * @property string $actor_id Id del actor que registra la identidad presencial obtenido del ws actores
 * @mixin Eloquent
 */
class Presential extends Model
{
	use HasFactory;

	public $timestamps = true;
	protected $table = "presential";
	protected $fillable = [
		'user_authentication_id',
		'dni_front',
		'dni_back',
		'user_photo',
		'actor_id',
	];

	public function ciudadanoAutenticacion()
	{
		return $this->belongsTo(UserAuth::class, 'user_authentication_id');
	}
}

