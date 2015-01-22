<?php

namespace Romby\Box\Services;

use Romby\Box\Http\HttpInterface;

class Collaborations extends AbstractService {

    /**
     * The HTTP interface.
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
     * Create a new collaboration.
     *
     * @param string      $token             the OAuth token.
     * @param int         $id                the id of the folder to create a collaboration for.
     * @param string      $role              the role to assign to the collaborator.
     * @param int|null    $collaboratorId    the id of the collaborator.
     * @param string|null $collaboratorType  the type of the collaborator, either user or group.
     * @param string|null $collaboratorLogin an email address (does not need to be a Box user). Omit if this is a
     *                                       group, or if you include the user ID.
     * @return array
     */
    public function create($token, $id, $role, $collaboratorId = null, $collaboratorType = null, $collaboratorLogin = null)
    {
        $options = [
            'json' => ['item' => ['id' => $id, 'type' => 'folder'], 'role' => $role]
        ];

        if( ! is_null($collaboratorId))
        {
            $options['json']['accessible_by'] = ['id' => $collaboratorId, 'type' => $collaboratorType];
        }
        else
        {
            $options['json']['accessible_by'] = ['login' => $collaboratorLogin];
        }

        return $this->postQuery($this->getFullUrl('/collaborations'), $token, $options);
    }

    /**
     * Get a collaboration.
     *
     * @param int    $id     the id of the collaboration.
     * @param string $token  the OAuth token.
     * @param array  $fields the fields to include in the response.
     * @return array the response.
     */
    public function get($id, $token, $fields = [])
    {
        $options = [];

        if( ! empty($fields)) $options['json'] = ['fields' => implode(',', $fields)];

        return $this->getQuery($this->getFullUrl('/collaborations/' . $id), $token, $options);
    }

    /**
     * Update the given collaboration.
     *
     * @param int         $id     the id of the collaboration.
     * @param string      $token  the OAuth token.
     * @param string|null $role   the new role of the collaborator.
     * @param string|null $status the new status of the collaboration.
     * @return array the response.
     */
    public function update($id, $token, $role = null, $status = null)
    {
        $options = [
            'json' => []
        ];

        if( ! is_null($role)) $options['json']['role'] = $role;

        if( ! is_null($role)) $options['json']['status'] = $status;

        return $this->putQuery($this->getFullUrl('/collaborations/' . $id), $token, $options);
    }

    /**
     * Delete the given collaboration.
     *
     * @param int    $id    the id of the collaboration.
     * @param string $token the OAuth token.
     * @return void
     */
    public function delete($id, $token)
    {
        $this->deleteQuery($this->getFullUrl('/collaborations/' . $id), $token);
    }

    /**
     * Get the pending collaborations for the current user.
     *
     * @param string $token the OAuth token.
     * @return array the response.
     */
    public function getPending($token)
    {
        $options = ['query' => ['status' => 'pending']];

        return $this->getQuery($this->getFullUrl('/collaborations'), $token, $options);
    }

}
