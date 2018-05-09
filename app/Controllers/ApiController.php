<?php

namespace App\Controllers;

class ApiController extends Controller
{
    public function index($request, $response)
    {
        return $response->withJson(['message' => 'Nice!']);
    }
}