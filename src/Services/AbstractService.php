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
            }
            elseif(is_bool($item))
            {
                return $item ? 'true' : 'false';
            }

            return $item;
        }, $params);

        return array_filter($params);
    }

    /**
     * Perform a GET query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $params  the parameters to send with the request.
     * @param array  $headers the headers to send with the request.
     * @return array the response to the query.
     */
    protected function getQuery($url, $token, $params = [], $headers = [])
    {
        $headers['Authorization'] = 'Bearer ' . $token;

        return $this->http->get($this->getFullUrl($url), $headers, $params);
    }

    /**
     * Perform a PUT query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $params  the parameters to send with the request.
     * @param array  $headers the headers to send with the request.
     * @return array the response to the query.
     */
    protected function putQuery($url, $token, $params = [], $headers = [])
    {
        $headers['Authorization'] = 'Bearer ' . $token;

        return $this->http->put($this->getFullUrl($url), $headers, $params);
    }

    /**
     * Perform a DELETE query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $params  the parameters to send with the request.
     * @param array  $headers the headers to send with the request.
     * @return void
     */
    protected function deleteQuery($url, $token, $params = [], $headers = [])
    {
        $headers['Authorization'] = 'Bearer ' . $token;

        $this->http->delete($this->getFullUrl($url), $headers, $params);
    }

    /**
     * Perform a POST query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $params  the parameters to send with the request.
     * @param array  $headers the headers to send with the request.
     * @return array the response to the query.
     */
    protected function postQuery($url, $token, $params = [], $headers = [])
    {
        $headers['Authorization'] = 'Bearer ' . $token;

        return $this->http->post($this->getFullUrl($url), $headers, $params);
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
}