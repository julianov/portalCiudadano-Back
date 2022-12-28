<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class CitizenInformation
 * @property string $email Dirección de mail declarada por el ciudadano para notificaciones
 * @property string $emailToken  Token de confirmación de email
 * @property string $fechaNacimiento  Fecha de Nacimiento declarada por del ciudadano para notificaciones por rango etario
 * @property string $celular  Nro de celular declarado por el ciudadano para notificaciones (3dig caracteristica+7dig nro)
 * @property string $departamentoId  Id del departamento provincial
 * @property string $localidadId  Id de la localidad provincial
 * @property string $domicilio  Calle del domicilio declarado por el ciudadano
 * @property string $numero  Nro de casa declarado por el ciudadano
 * @property User $ciudadano
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

