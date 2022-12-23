<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CitizenAuth
 * @property string $nivel Nivel que le corresponde al tipo de autenticación
 * @property DateTimeInterface $fecha_autenticacion Fecha de autenticación
 * @mixin Eloquent
 */
class CitizenAuth extends Model
{
	use HasFactory, HasUuids;

	public $timestamps = true;
	protected $table = "ciudadano_autenticacion";
	protected $keyType = 'string';

	protected $fillable = [
		'citizen_id',
		'autenticacion_tipo_id',
		'nivel',
		'fecha_autenticacion',
	];

	public function ciudadano()
	{
		return $this->belongsTo(Citizen::class, 'citizen_id');
	}

	public function autenticacionTipo()
	{
		return $this->belongsTo(AuthenticationType::class, 'autenticacion_tipo_id');
	}
}
