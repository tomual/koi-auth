<?php

namespace App\Controllers\Auth;

use App\Models\Gardener;
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
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            $this->flash->addMessage('error', 'Invalid login.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignOut($request, $response)
    {
        $this->auth->logout();

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignUp($request, $response)
    {
        $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'username' => v::noWhitespace()->notEmpty()->alnum(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $koi = Gardener::create([
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        $this->auth->attempt($request->getParam('email'), $request->getParam('password'));

        $this->flash->addMessage('info', 'You have successfully signed up!');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}