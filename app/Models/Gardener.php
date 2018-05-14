<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gardener extends Model
{
    protected $fillable = [
        'username',
        'email',
        'password'
    ];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}