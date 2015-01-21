<?php

namespace Romby\Box\Services;

use Romby\Box\Http\HttpInterface;

class Comments extends AbstractService {

    /**
     * THe HTTP interface.
     *
     * @var HttpInterface
     */
    protected $http;

    /**
     * Instantiate the class and inject the dependencies.
     *
     * @param HttpInterface $http the HTTP interface.
     */
    public function __construct(HttpInterface $http)
    {
        $this->http = $http;
    }

    public function create($token, $id, $type, $message, $taggedMessage = null)
    {
        $options = [
            'json' => ['item' => ['id' => $id, 'type' => $type], 'message' => $message]
        ];

        if( ! is_null($taggedMessage)) $options['json']['tagged_message'] = $taggedMessage;

        return $this->postQuery($this->getFullUrl('/comments/'), $token, $options);
    }
}
