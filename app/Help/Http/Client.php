<?php
/**
 * Created by PhpStorm.
 * User: ajl
 * Date: 2018/12/1
 * Time: 上午11:33
 */

namespace App\Help\Http;

use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @method ResponseInterface get(string | UriInterface $uri, array $options = [])
 * @method ResponseInterface head(string | UriInterface $uri, array $options = [])
 * @method ResponseInterface put(string | UriInterface $uri, array $options = [])
 * @method ResponseInterface post(string | UriInterface $uri, array $options = [])
 * @method ResponseInterface patch(string | UriInterface $uri, array $options = [])
 * @method ResponseInterface delete(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface getAsync(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface headAsync(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface putAsync(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface postAsync(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface patchAsync(string | UriInterface $uri, array $options = [])
 * @method Promise\PromiseInterface deleteAsync(string | UriInterface $uri, array $options = [])
 */
class Client
{

    protected static $instance;
    protected $client = null;

    function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    static public function __callStatic($method, $args)
    {
        return (self::getInstance())->client->__call($method, $args);
    }

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    static function getJson($url, $option = [])
    {
        Log::debug($url, $option);
        $Response = (self::getInstance())->client->get($url, $option);
        Log::debug($Response->getBody());
        return json_decode($Response->getBody());
    }
}