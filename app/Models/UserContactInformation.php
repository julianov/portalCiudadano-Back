<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class UserContactInformation
 * @property string $email Dirección de mail declarada por el ciudadano para notificaciones
 * @property string $emailToken  Token de confirmación de email
 * @property string $birthday  Fecha de Nacimiento declarada por del ciudadano para notificaciones por rango etario
 * @property string $cellphone_number  Nro de celular declarado por el ciudadano para notificaciones (3dig caracteristica+7dig nro)
 * @property string $department_id  Id del departamento provincial
 * @property string $locality_id  Id de la localidad provincial
 * @property string $address_street  Calle del domicilio declarado por el ciudadano
 * @property string $address_number  Nro de casa declarado por el ciudadano
 * @property User $user
 * @mixin Eloquent
 */
class UserContactInformation extends Model
{
	use HasFactory, HasUuids;

	public $timestamps = true;
	protected $table = "user_contact";
	protected $keyType = 'string';

	protected $fillable = [
		'email',
		'birthday',
		'cellphone_number',
		'department_id',
		'locality_id',
		'address_street',
		'address_number',
		'apartment',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'cellphone_number_verified_at'=> 'datetime',
	];


	/**
	 * Get the ciudadano that owns the CitizenInformation
	 *
	 * @return BelongsTo
	 */
	public function ciudadano(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}

