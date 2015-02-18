<?php namespace Romby\Box\Services;

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
     * @param int         $itemId            the id of the item to comment.
     * @param string      $itemType          the type of the item to comment.
     * @param string      $message       the comment message.
     * @param string|null $taggedMessage the comment message, with tags.
     * @return array the response.
     */
    public function create($token, $itemId, $itemType, $message, $taggedMessage = null)
    {
        $options = [
            'json' => ['item' => ['id' => $itemId, 'type' => $itemType], 'message' => $message]
        ];

        if( ! is_null($taggedMessage)) $options['json']['tagged_message'] = $taggedMessage;

        return $this->postQuery($this->getFullUrl('/comments/'), $token, $options);
    }

    /**
     * Get information about a comment.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the comment.
     * @return array the response.
     */
    public function get($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/comments/' . $id), $token);
    }

    /**
     * Update the message of the given comment.
     *
     * @param string $token   the OAuth token.
     * @param int    $id      the id of the comment.
     * @param string $message the new message.
     * @return array the response.
     */
    public function update($token, $id, $message)
    {
        $options = [
            'json' => ['message' => $message]
        ];

        return $this->putQuery($this->getFullUrl('/comments/' . $id), $token, $options);
    }

    /**
     * Delete the given comment.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the comment.
     * @return void
     */
    public function delete($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/comments/' . $id), $token);
    }

}
