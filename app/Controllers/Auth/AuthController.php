<?php

namespace App\Controllers\Auth;

use App\Models\Koi;
use App\Controllers\Controller;

class AuthController extends Controller
{
    public function getSignUp($request, $response)
    {
        $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        var_dump($request->getParams());

        $koi = Koi::create([
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}