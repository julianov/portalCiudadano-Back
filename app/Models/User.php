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
 * @property int $id
 * @property string $cuil Cuil del ciudadano que se utilizará como USUARIO del portal
 * @property string $prs_id Código que identifica a la persona en BDU
 * @property string $name
 * @property string $last_name
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
        'name',
        'last_name',
        'password',
    ];

    protected $hidden = [
        'password',
    ];


}
