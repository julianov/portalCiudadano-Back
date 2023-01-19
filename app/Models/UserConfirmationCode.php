<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserConfirmationCode extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable =[ 
        'id',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
