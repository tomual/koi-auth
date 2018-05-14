<?php

namespace App\Auth;

interface Auth
{
    public function user();
    public function check();
    public function attempt($email, $password);
    public function logout();
}