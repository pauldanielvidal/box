<?php namespace Romby\Box\Http;

interface HttpInterface {

    public function get($url, $options);

    public function post($url, $options, $file);

    public function put($url, $options);

    public function delete($url, $options);

}
