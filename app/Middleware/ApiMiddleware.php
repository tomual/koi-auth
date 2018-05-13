<?php

namespace App\Middleware;

use App\Models\Pond;
use App\Models\Log;

class ApiMiddleware extends Middleware
{
    const LIMIT = 1000;

    public function __invoke($request, $response, $next)
    {
        $header = str_replace('Basic ', '', $request->getHeader("Authorization")[0]);
        $header = base64_decode($header);
        $data = explode(':', $header);

        if(count($data) != 2) {
            return $response->withJson(['error' => true, 'message' => 'Invalid authorization header.']);
        }

        $api_key = $data[0];
        $pond = Pond::where('api_key', $api_key)->first();

        if(!$pond) {
            return $response->withJson(['error' => true, 'message' => 'Invalid API key.']);
        }

        $provided_signature = $data[1];
        $valid_signature = hash('md5', $pond->api_secret . date('Y-m-d'));

        if ($provided_signature != $valid_signature) {
            return $response->withJson(['error' => true, 'message' => 'Invalid signature.']);
        }

        $usage = Log::where('pond_id', $pond->id)->whereRaw('Date(created_at) = CURDATE()')->get()->count();
        if($usage > self::LIMIT) {
            return $response->withJson(['error' => true, 'message' => 'Usage limit for API reached.']);
        }

        $log = new Log();
        $log->pond_id = $pond->id;
        $log->endpoint = $request->getUri()->getPath();
        $log->save();

        $response = $next($request, $response);
        return $response;
    }
}