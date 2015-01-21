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

    /**
     * Create a new comment.
     *
     * @param string      $token         the OAuth token.
     * @param int         $id            the id of the item to comment.
     * @param string      $type          the type of the item to comment.
     * @param string      $message       the comment message.
     * @param string|null $taggedMessage the comment message, with tags.
     * @return array the response.
     */
    public function create($token, $id, $type, $message, $taggedMessage = null)
    {
        $options = [
            'json' => ['item' => ['id' => $id, 'type' => $type], 'message' => $message]
        ];

        if( ! is_null($taggedMessage)) $options['json']['tagged_message'] = $taggedMessage;

        return $this->postQuery($this->getFullUrl('/comments/'), $token, $options);
    }

}
