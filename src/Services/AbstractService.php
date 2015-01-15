<?php namespace Romby\Box\Services;

use Romby\Box\Http\HttpInterface;

class AbstractService {

    /**
     * The HTTP interface.
     *
     * @var HttpInterface
     */
    protected $http;

    /**
     * The base API url.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.box.com/2.0';

    /**
     * Instantiate the class and inject the dependencies.
     *
     * @param HttpInterface $http the HTTP interface.
     */
    public function __construct(HttpInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Construct a query from the given parameters by flattening array parameters and removing empty parameters.
     *
     * @param array $params the parameters.
     * @return array the query.
     */
    protected function constructQuery($params)
    {
        $params = array_map(function ($item)
        {
            if(is_array($item))
            {
                return implode(',', $item);
            } elseif(is_bool($item))
            {
                return $item ? 'true' : 'false';
            }

            return $item;
        }, $params);

        return array_filter($params, function ($item)
        {
            return $item !== null && $item !== '';
        });
    }

    /**
     * Perform a GET query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $options the options to send with the request.
     * @return array the response to the query.
     */
    protected function getQuery($url, $token, $options = [])
    {
        $options = $this->addAuthorizationHeader($token, $options);

        return $this->http->get($url, $options);
    }

    /**
     * Perform a GET query to the given url, expecting a redirect.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $options the options to send with the request.
     * @return array the response to the query.
     */
    protected function downloadQuery($url, $token, $options = [], $name)
    {
        $options = $this->addAuthorizationHeader($token, $options);

        return $this->http->download($url, $options, $name);
    }

    /**
     * Perform a PUT query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $options the options to send with the request.
     * @return array the response to the query.
     */
    protected function putQuery($url, $token, $options = [])
    {
        $options = $this->addAuthorizationHeader($token, $options);

        return $this->http->put($url, $options);
    }

    /**
     * Perform a DELETE query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $options the options to send with the request.
     * @return void
     */
    protected function deleteQuery($url, $token, $options = [])
    {
        $options = $this->addAuthorizationHeader($token, $options);

        $this->http->delete($url, $options);
    }

    /**
     * Perform a POST query to the given url.
     *
     * @param string      $url     the url to send the query to.
     * @param string      $token   the OAuth token.
     * @param array       $options the options to send with the request.
     * @param string|null $file    the path to a file to send with the query.
     * @return array the response to the query.
     */
    protected function postQuery($url, $token, $options = [], $file = null)
    {
        $options = $this->addAuthorizationHeader($token, $options);

        return $this->http->post($url, $options, $file);
    }

    /**
     * Get the full url for the given path.
     *
     * @param string $path the path.
     * @return string the full url.
     */
    protected function getFullUrl($path)
    {
        return $this->baseUrl . $path;
    }

    /**
     * Add an authorization header to the given options.
     *
     * @param string $token   the OAuth token.
     * @param array  $options the existing options.
     * @return array the options with the authorization header attached.
     */
    protected function addAuthorizationHeader($token, $options)
    {
        if( ! isset($options['headers']))
        {
            $options['headers'] = [];
        }

        $options['headers']['Authorization'] = 'Bearer ' . $token;

        return $options;
    }

}
