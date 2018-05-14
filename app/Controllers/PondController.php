<?php

namespace App\Controllers;

use App\Models\Pond;
use Respect\Validation\Validator as v;

class PondController extends Controller
{
    public function getPonds($request, $response)
    {
        $ponds = Pond::all();
        $this->view->render($response, 'pond/list.twig', compact('ponds'));
    }

    public function getCreatePond($request, $response)
    {
        $this->view->render($response, 'pond/create.twig');
    }

    public function postCreatePond($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'name' => v::noWhitespace()->notEmpty()
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('pond.create'));
        }

        $api_key = hash('md5', $request->getParam('name'));
        $api_secret = hash('md5', $api_key . strtotime("now"));

        $pond = Pond::create([
            'name' => $request->getParam('name'),
            'api_key' => $api_key,
            'api_secret' => $api_secret,
        ]);

        return $response->withRedirect($this->router->pathFor('pond.list'));
    }

    public function postRegenerateSecret($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'id' => v::notEmpty()
        ]);

        if($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('pond.list'));
        }

        $pond = Pond::find($request->getParam('id'));
        $pond->api_secret = bin2hex(openssl_random_pseudo_bytes(16));
        $pond->save();

        $this->flash->addMessage('info', 'API secret has been regenerated.');

        return $response->withRedirect($this->router->pathFor('pond.list'));
    }
}