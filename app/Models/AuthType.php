<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthType
 * @property string $id Identifica univocamente el registro
 * @property AuthTypeEnum $descripcion
 * @mixin Eloquent
 */
class AuthType extends Model
{
	use HasFactory, HasUuids;

	public $timestamps = true;
	protected $table = "autenticacion_tipos";
	protected $keyType = 'string';

	protected $fillable = [
		'descripcion',
	];

}