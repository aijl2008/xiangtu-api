<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2019/1/2
 * Time: 下午8:33
 */

namespace App\Models;


use GuzzleHttp\Client;

class MiniProgram
{
    /**
     * @var Client|null
     */
    protected $client = null;

    function __construct()
    {
        $this->client = new Client();
    }

    function getWxaCodeUnLimit($scene, $page)
    {
        $access_token = json_decode($this->token())->access_token;
        $response = $this->client->request('POST', 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token,
            [
                'body' => json_encode([
                    'scene' => $scene,
                    'page' => $page
                ])]
        );
        return $response;
    }


    function token()
    {
        $response = $this->client->request('GET', 'https://api.weixin.qq.com/cgi-bin/token?' . http_build_query(
                [
                    'grant_type' => 'client_credential',
                    'appid' => config('wechat.mini_program.default.app_id'),
                    'secret' => config('wechat.mini_program.default.secret')
                ]
            ));
        return $response->getBody()->getContents();
    }
}