<?php

namespace App\Middleware;

use App\Models\Pond;

class ApiMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $key = $request->getParam('key');
        $secret = $request->getParam('secret');

        $pond = Pond::where('api_key', $key)->first();

        if(!$pond) {
            return $response->withJson(['error' => true, 'message' => 'Invalid API key.']);
        }

        if ($secret != $pond->api_secret) {
            return $response->withJson(['error' => true, 'message' => 'Incorrect API secret.']);
        }

        $response = $next($request, $response);
        return $response;
    }
}