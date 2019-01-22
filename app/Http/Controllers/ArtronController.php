<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class ArtronController extends Controller
{
    function __invoke($encodedUrl)
    {
        $url = base64_decode($encodedUrl);
        if (!$url) {
            abort(403);
        }
        try {
            $client = new Client();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Referer' => 'https://news.artron.net/',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36'
                ]
            ]);
            $data = $response->getBody()->getContents();
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
