<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pond extends Model
{
    protected $fillable = [
        'gardener',
        'name',
        'api_key',
        'api_secret'
    ];

    public function kois()
    {
        return $this->hasMany('App\Models\Koi');
    }
}