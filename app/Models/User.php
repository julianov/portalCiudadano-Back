<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property string $cuil
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $password
 * @mixin Eloquent
 */
class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;


	protected $fillable = [
		'cuil',
		'nombre',
		'apellido',
		'email',
		'password',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
	];
}
