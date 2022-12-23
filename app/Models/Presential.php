<?php

namespace App\Models;

use DateTimeInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Presential
 * @property string $dniFrente
 * @property string $dniDorso
 * @property string $foto
 * @property string $actorId Id del actor que registra la identidad presencial obtenido del ws actores
 * @property DateTimeInterface $fechaAutenticacion Fecha de autenticación
 * @mixin Eloquent
 */
class Presential extends Model
{
	use HasFactory;

	public $timestamps = true;
	protected $table = "presencial";
	protected $fillable = [
		'citizen_auth_id',
		'dni_frente',
		'dni_dorso',
		'foto',
		'actor_id',
		'fecha_autenticacion',
	];

	public function ciudadanoAutenticacion()
	{
		return $this->belongsTo(CitizenAuth::class, 'citizen_auth_id');
	}
}
