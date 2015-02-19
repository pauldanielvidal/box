<?php namespace Romby\Box\Services;

class Groups extends AbstractService {

    /**
     * Create a new group.
     *
     * @param string $token the OAuth token.
     * @param string $name  the name of the group.
     * @return array the new group.
     */
    public function create($token, $name)
    {
        return $this->postQuery($this->getFullUrl('/groups'), $token, ['json' => compact('name')]);
    }

    /**
     * Get a group.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group.
     * @return array the group.
     */
    public function get($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/groups/' . $id), $token);
    }

    /**
     * Get all groups.
     *
     * @param string $token the OAuth token.
     * @return array the groups.
     */
    public function all($token)
    {
        return $this->getQuery($this->getFullUrl('/groups'), $token);
    }

    /**
     * Update a group.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group.
     * @param string $name  the new name of the group.
     * @return array the updated group.
     */
    public function update($token, $id, $name)
    {
        return $this->putQuery($this->getFullUrl('/groups/' . $id), $token, ['json' => compact('name')]);
    }

    /**
     * Delete a group.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group.
     * @return void
     */
    public function delete($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/groups/' . $id), $token);
    }

    /**
     * Get the Membership list for a Group
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group.
     * @return array group membership entries.
     */
    public function getMembershipList($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/groups/' . $id . '/memberships'), $token);
    }

    /**
     * Get all Group Memberships for a User
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the user.
     * @return array group membership entries.
     */
    public function getUsersGroups($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/users/' . $id . '/memberships'), $token);
    }

    /**
     * Get a Group Membership Entry
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group membership entry.
     * @return array the group membership entry.
     */
    public function getMembershipEntry($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/group_memberships/' . $id), $token);
    }

    /**
     * Add a Member to a Group
     *
     * @param string      $token   the OAuth token.
     * @param int         $userId  the ID of the user.
     * @param int         $groupId the ID of the group.
     * @param string|null $role    the role of the member.
     * @return array group membership entry.
     */
    public function addUserToGroup($token, $userId, $groupId, $role = null)
    {
        $options = [
            'json' => [
                'user' => ['id' => $userId],
                'group' => ['id' => $groupId]
            ]
        ];

        if(isset($role)) $options['json']['role'] = $role;

        return $this->postQuery($this->getFullUrl('/group_memberships'), $token, $options);
    }

    /**
     * Updates a group membership
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the membership entry.
     * @param string $role  the role of the user.
     * @return array group membership entry.
     */
    public function updateMembershipEntry($token, $id, $role)
    {
        $options = [
            'json' => ['role' => $role]
        ];

        return $this->putQuery($this->getFullUrl('/group_memberships/' . $id), $token, $options);
    }

    /**
     * Deletes a group membership
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the user.
     * @return void.
     */
    public function deleteMembershipEntry($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/group_memberships/' . $id), $token);
    }

    /**
     * Gets all collaborations for a group
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the group.
     * @return array collaborations.
     */
    public function getAllCollaborations($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/groups/' . $id . '/collaborations'), $token);
    }
}
