<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class CosController extends Controller
{
    function __invoke($url)
    {
        $url = base64_decode($url);
        if (!$url) {
            abort(403);
        }
        try {
            $client = new Client();
            $data = $client->request('GET', $url)->getBody()->getContents();
            return response($data, 200, [
                'Content-Type' => 'image/png',
            ]);
        } catch (\Exception $e) {
            return response(readfile(base_path("./public/images/default.png")), 200, [
                'Content-Type' => 'image/png',
            ]);
        }

    }
}
