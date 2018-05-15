<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Koi extends Model
{
    protected $fillable = [
        'pond_id',
        'username',
        'email',
        'password',
    ];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}