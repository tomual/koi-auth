<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use \Firebase\JWT\JWT;

$app->get('/', 'HomeController:index')->setName('home');

$app->group('', function () {
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');
    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');
    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');
})->add(new AuthMiddleware($container));

$app->post('/authorize', function ($request, $response, $args) {

    $key = $request->getParam('key');
    $secret = $request->getParam('secret');

    if ($secret != 'testing') {
        return $this->response->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);
    }

    $settings = $this->get('settings');
    $token = JWT::encode(['id' => $key], $settings['jwt']['secret'], "HS256");
    return $this->response->withJson(['token' => $token]);
});