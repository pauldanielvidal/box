<?php namespace Romby\Box\Http\Adapters;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Romby\Box\Http\HttpInterface;

class GuzzleHttpAdapter implements HttpInterface {

    protected $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function get($url, $options)
    {
        return $this->guzzle->get($url, $options)->json();
    }

    public function post($url, $options, $file = null)
    {
        if($file)
        {
            $options['body']['file'] = fopen($file, 'r');
        }

        return $this->guzzle->post($url, $options)->json();
    }

    public function put($url, $options)
    {
        return $this->guzzle->put($url, $options)->json();
    }

    public function delete($url, $options)
    {
        return $this->guzzle->delete($url, $options)->json();
    }

}
