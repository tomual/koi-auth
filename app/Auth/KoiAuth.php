<?php

namespace App\Auth;

use App\Models\Koi;

class KoiAuth implements Auth
{
    public function user()
    {
        if($this->check()) {
            return Koi::find($_SESSION['user']);
        }
        return;
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt($email, $password)
    {
        $user = Koi::where('email', $email)->first();

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return $user;
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }
}