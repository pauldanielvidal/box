<?php namespace Romby\Box\Http;

interface HttpInterface {

    public function get($url, $headers, $query);

    public function post($url, $headers, $query);

    public function put($url, $headers, $query);

    public function delete($url, $headers, $query);

}
