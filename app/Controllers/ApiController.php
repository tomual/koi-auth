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
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'username' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        if($validation->failed()) {
            return $response->withJson(['error' => true, 'message' => $_SESSION['errors']]);
        }

        $koi = Koi::create([
            'email' => $request->getParam('email'),
            'username' => $request->getParam('username'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

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

        return $response->withJson(['message' => $auth]);
    }
}