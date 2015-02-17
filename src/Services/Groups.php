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

}
