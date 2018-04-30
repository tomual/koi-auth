<?php

namespace App\Controllers\Auth;

use App\Models\Koi;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller
{
    public function getSignIn($request, $response)
    {
        $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response)
    {

    }

    public function getSignUp($request, $response)
    {
        $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'username' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $koi = Koi::create([
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        return $response->withRedirect($this->router->pathFor('home'));
    }
}