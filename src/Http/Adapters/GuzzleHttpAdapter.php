<?php namespace Romby\Box\Http\Adapters;

use GuzzleHttp\Client;
use Romby\Box\Http\HttpInterface;

class GuzzleHttpAdapter implements HttpInterface {

    /**
     * The Guzzle client.
     *
     * @var Client
     */
    protected $guzzle;

    /**
     * Instantiate the class and inject the dependencies.
     *
     * @param Client $guzzle the Guzzle client.
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Send a GET request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @return array the response.
     */
    public function get($url, $options)
    {
        return $this->guzzle->get($url, $options)->json();
    }

    /**
     * Download a file from the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @param string $name    the filename to store the downloaded file in.
     * @return void
     */
    public function download($url, $options, $name)
    {
        $result = $this->guzzle->get($url, $options);

        file_put_contents($name, $result->getBody());
    }

    /**
     * Send a POST request to the given url with the given options.
     *
     * @param string      $url     the url.
     * @param array       $options the options.
     * @param string|null $file    the path to a file to upload.
     * @return array the response.
     */
    public function post($url, $options, $file = null)
    {
        if($file)
        {
            $options['body']['file'] = fopen($file, 'r');
        }

        return $this->guzzle->post($url, $options)->json();
    }

    /**
     * Send a PUT request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @return array the response.
     */
    public function put($url, $options)
    {
        return $this->guzzle->put($url, $options)->json();
    }

    /**
     * Send a DELETE request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @return array the response.
     */
    public function delete($url, $options)
    {
        return $this->guzzle->delete($url, $options)->json();
    }

}
