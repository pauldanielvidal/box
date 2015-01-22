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

    /**
     * Get information about a comment.
     *
     * @param int    $id    the id of the comment.
     * @param string $token the OAuth token.
     * @return array the response.
     */
    public function get($id, $token)
    {
        return $this->getQuery($this->getFullUrl('/comments/' . $id), $token);
    }

    /**
     * Update the message of the given comment.
     *
     * @param int    $id      the id of the comment.
     * @param string $token   the OAuth token.
     * @param string $message the new message.
     * @return array the response.
     */
    public function update($id, $token, $message)
    {
        $options = [
            'json' => ['message' => $message]
        ];

        return $this->putQuery($this->getFullUrl('/comments/' . $id), $token, $options);
    }

    /**
     * Delete the given comment.
     *
     * @param int    $id    the id of the comment.
     * @param string $token the OAuth token.
     * @return void
     */
    public function delete($id, $token)
    {
        $this->deleteQuery($this->getFullUrl('/comments/' . $id), $token);
    }

}
