<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Citizen
 * @property string $cuil Cuil del ciudadano que se utilizará como USUARIO del portal
 * @property string $nombre
 * @property string $apellido
 * @property string $prs_id Código que identifica a la persona en BDU
 * @mixin Eloquent
 */
class Citizen extends Model
{
	use HasFactory, HasUuids;

	public $timestamps = true;
	protected $table = "ciudadanos";
	protected $keyType = 'string';

	protected $fillable = [
		'prs_id',
		'cuil',
		'nombre',
		'apellido',
	];
}
