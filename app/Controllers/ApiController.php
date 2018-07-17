<?php

namespace App\Controllers;

use App\Models\Koi;
use Respect\Validation\Validator as v;

class ApiController extends Controller
{
    public function index($request, $response)
    {
        return $response->withJson(['message' => 'Nice!']);
    }

    public function signup($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->koiEmailAvailable(),
            'username' => v::noWhitespace()->notEmpty()->alnum('_')->koiUsernameAvailable(),
            'password' => v::noWhitespace()->notEmpty()->length(6),
        ]);

        if($validation->failed()) {
            return $response->withJson(['error' => true, 'message' => $_SESSION['errors']]);
        }

        $koi = Koi::create([
            'pond_id' => $_SESSION['pond_id'],
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        unset($koi->password);

        return $response->withJson(['message' => $koi]);
    }

    public function login($request, $response)
    {
        $auth = $this->koiAuth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            return $response->withJson(['error' => true, 'message' => 'Invalid login.']);
        }
        
        unset($auth->password);

        return $response->withJson(['message' => $auth]);
    }
}