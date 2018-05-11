<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\ApiMiddleware;

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/pond/list', 'PondController:getPonds')->setName('pond.list');
$app->get('/pond/create', 'PondController:getCreatePond')->setName('pond.create');
$app->post('/pond/create', 'PondController:postCreatePond');

$app->group('', function () {
    $this->post('/api/user/create', 'ApiController:signup');
    $this->post('/api/user/login', 'ApiController:index');
})->add(new ApiMiddleware($container));

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
