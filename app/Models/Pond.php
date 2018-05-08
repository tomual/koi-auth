<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pond extends Model
{
    protected $fillable = [
        'name',
        'api_key',
        'api_secret'
    ];
}