<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Koi extends Model
{
    // protected $table = 'koi';

    protected $fillable = [
        'username',
        'email',
        'password'
    ];
}