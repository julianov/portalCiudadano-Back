<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum AuthTypeEnum {
    case REGISTRADO;
    case ANSES;
    case AFIP;
    case MIARGENTINA;
    case PRESENCIAL;
}

/**
 * Class AuthType
 * @property string $id Identifica univocamente el registro
 * @property AuthTypeEnum $descripcion
 */
class AuthType extends Model
{
    use HasFactory, HasUuids;

    protected $table = "autenticacion_tipos";

    public $timestamps = true;

    protected $keyType = 'string';

    protected $fillable = [
        'descripcion',
    ];

}
