<?php

namespace Romby\Box\Services;

class Collaborations extends AbstractService {

    /**
     * Create a new collaboration.
     *
     * @param string      $token             the OAuth token.
     * @param int         $folderId          the id of the folder to create a collaboration for.
     * @param string      $role              the role to assign to the collaborator.
     * @param int|null    $collaboratorId    the id of the collaborator.
     * @param string|null $collaboratorType  the type of the collaborator, either user or group.
     * @param string|null $collaboratorLogin an email address (does not need to be a Box user). Omit if this is a
     *                                       group, or if you include the user ID.
     * @return array
     */
    public function create($token, $folderId, $role, $collaboratorId = null, $collaboratorType = null, $collaboratorLogin = null)
    {
        $options = [
            'json' => ['item' => ['id' => $folderId, 'type' => 'folder'], 'role' => $role]
        ];

        if( ! is_null($collaboratorId))
        {
            $options['json']['accessible_by'] = ['id' => $collaboratorId, 'type' => $collaboratorType];
        } else
        {
            $options['json']['accessible_by'] = ['login' => $collaboratorLogin];
        }

        return $this->postQuery($this->getFullUrl('/collaborations'), $token, $options);
    }

    /**
     * Get a collaboration.
     *
     * @param string $token  the OAuth token.
     * @param int    $id     the id of the collaboration.
     * @param array  $fields the fields to include in the response.
     * @return array the response.
     */
    public function get($token, $id, $fields = [])
    {
        $options = [];

        if( ! empty($fields)) $options['json'] = ['fields' => implode(',', $fields)];

        return $this->getQuery($this->getFullUrl('/collaborations/' . $id), $token, $options);
    }

    /**
     * Update the given collaboration.
     *
     * @param string      $token  the OAuth token.
     * @param int         $id     the id of the collaboration.
     * @param string|null $role   the new role of the collaborator.
     * @param string|null $status the new status of the collaboration.
     * @return array the response.
     */
    public function update($token, $id, $role = null, $status = null)
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
     * @param string $token the OAuth token.
     * @param int    $id    the id of the collaboration.
     * @return void
     */
    public function delete($token, $id)
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
