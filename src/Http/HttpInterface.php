<?php namespace Romby\Box\Http;

use Romby\Box\Http\Exceptions\NameConflictException;
use Romby\Box\Http\Exceptions\NotFoundException;

interface HttpInterface {

    /**
     * Send a GET request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @throws NotFoundException if the file is not found.
     * @return array the response.
     */
    public function get($url, $options);

    /**
     * Download a file from the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @param string $name    the filename to store the downloaded file in.
     * @return void
     */
    public function download($url, $options, $name);

    /**
     * Send a POST request to the given url with the given options.
     *
     * @param string      $url     the url.
     * @param array       $options the options.
     * @param string|null $file    the path to a file to upload.
     * @return array the response.
     */
    public function post($url, $options, $file = null);

    /**
     * Send a PUT request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @return array the response.
     */
    public function put($url, $options);

    /**
     * Send a DELETE request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @return array the response.
     */
    public function delete($url, $options);

    /**
     * Send an OPTIONS request to the given url with the given options.
     *
     * @param string $url     the url.
     * @param array  $options the options.
     * @throws NameConflictException if the name is already in use.
     * @return array the response.
     */
    public function options($url, $options);

}
