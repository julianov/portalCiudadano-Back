<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserValidationToken extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = "user_validation_token";

    protected $fillable =[
        'id',
        'user_id',
        'val_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
