<?php namespace Romby\Box\Http\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Romby\Box\Http\HttpInterface;

class GuzzleHttpAdapter implements HttpInterface {

    protected $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function get($url, $headers, $query)
    {
        return $this->guzzle->get($url, [
            'headers' => $headers,
            'query' => $query
        ])->json();
    }

    public function post($url, $headers, $query)
    {
        return $this->guzzle->post($url, [
            'headers' => $headers,
            'json' => $query
        ])->json();
    }

    public function put($url, $headers, $query)
    {
        return $this->guzzle->put($url, [
            'headers' => $headers,
            'json' => $query
        ])->json();
    }

    public function delete($url, $headers, $query)
    {
        return $this->guzzle->delete($url, [
            'headers' => $headers,
            'query' => $query
        ])->json();
    }

}
