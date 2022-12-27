<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property string $cuil Cuil del ciudadano que se utilizará como USUARIO del portal
 * @property string $prs_id Código que identifica a la persona en BDU
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $password
 * @mixin Eloquent
 */
class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = "users";

    protected $fillable =[
        'cuil',
        'prs_id',
        'nombre',
        'apellido',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    
}
