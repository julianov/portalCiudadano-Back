<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CitizenAuth
 * @property string $nivel Nivel que le corresponde al tipo de autenticación
 * @property DateTimeInterface $fecha_autenticacion Fecha de autenticación
 * @mixin Eloquent
 */
class UserAuth extends Model
{
	use HasFactory, HasUuids;

	public $timestamps = true;
	protected $table = "user_authentication";
	protected $keyType = 'string';

	protected $fillable = [
		'user_id',
		'authentication_types_id',
		'auth_level',
	];

	public function ciudadano(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function autenticacionTipo(): BelongsTo
	{
		return $this->belongsTo(AuthType::class, 'autenticacion_tipo_id');
	}
}


