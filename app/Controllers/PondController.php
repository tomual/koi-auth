<?php

namespace App\Controllers;

use App\Models\Pond;
use App\Models\Koi;
use App\Models\Log;
use Respect\Validation\Validator as v;

class PondController extends Controller
{
    public function viewPond($request, $response, $args)
    {
        if($args['id']) {
            $pond = Pond::find($args['id']);
            if($pond) {
                $kois = Koi::where('pond_id', $pond->id)->get();
                $usage = [
                    'day' => Log::where('pond_id', $pond->id)->whereBetween('created_at', [date('Y-m-d', strtotime('today')), date('Y-m-d', strtotime('tomorrow'))])->get()->count(),
                    'month' => Log::where('pond_id', $pond->id)->whereBetween('created_at', [date('Y-m-01', strtotime('today')), date('Y-m-d', strtotime('tomorrow'))])->get()->count(),
                    'total' => Log::where('pond_id', $pond->id)->count()
                ];
            }
        }
        $this->view->render($response, 'pond/view.twig', compact('pond', 'usage', 'kois'));
    }

    public function getPonds($request, $response)
    {
        $ponds = Pond::where('gardener', $this->auth->user()->id)->get();
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
            'gardener' => $this->auth->user()->id,
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

        $pond = Pond::where('id', $request->getParam('id'))->where('gardener', $this->auth->user()->id)->first();
        if(!$pond) {
            $this->flash->addMessage('error', 'Invalid pond selection.');
        }
        $pond->api_secret = bin2hex(openssl_random_pseudo_bytes(16));
        $pond->save();

        $this->flash->addMessage('info', 'API secret has been regenerated.');

        return $response->withRedirect($this->router->pathFor('pond.view', ['id' => $pond->id]));
    }
}