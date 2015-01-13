<?php namespace Romby\Box\Services;

class Folders extends AbstractService {

    /**
     * Get the items in a folder.
     *
     * @param int      $id     the id of the folder.
     * @param string   $token  the OAuth token.
     * @param array    $fields attribute(s) to include in the response
     * @param int|null $limit  the maximum number of items to return in a page.
     * @param int|null $offset the offset at which to begin the response.
     * @return array the items.
     */
    public function getItems($id, $token, array $fields = [], $limit = null, $offset = null)
    {
        $query = $this->constructQuery(compact('fields', 'limit', 'offset'));

        return $this->getQuery('/folders/' . $id . '/items', $token, $query);
    }

    /**
     * Create a new folder.
     *
     * @param string $token  the OAuth token.
     * @param string $name   the name of the new folder.
     * @param int    $parent the ID of the parent of the folder.
     * @return array the new folder.
     */
    public function create($token, $name, $parent)
    {
        $query = ['name' => $name, 'parent' => ['id' => $parent]];

        return $this->postQuery('/folders', $token, $query);
    }

    /**
     * Get the given folder.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return array the folder.
     */
    public function get($id, $token)
    {
        return $this->getQuery('/folders/' . $id, $token);
    }

    /**
     * Update the given folder.
     *
     * @param int         $id      the id of the folder.
     * @param string      $token   the OAuth token.
     * @param array       $params  the parameters to set on the folder.
     * @param string|null $version if set, the folder will only be updated if this is the latest version.
     * @return array the updated folder.
     */
    public function update($id, $token, $params, $version = null)
    {
        $headers = isset($version) ? ['If-Match' => $version] : [];

        return $this->putQuery('/folders/' . $id, $token, $params, $headers);
    }

    /**
     * Delete the given folder.
     *
     * @param int    $id        the id of the folder.
     * @param string $token     the OAuth token.
     * @param array  $fields    attribute(s) to include in the response.
     * @param null   $recursive whether to delete this folder if it has items inside of it.
     * @param null   $version
     * @return void
     */
    public function delete($id, $token, $fields = [], $recursive = null, $version = null)
    {
        $params = $this->constructQuery(compact('fields', 'recursive'));

        $headers = isset($version) ? ['If-Match' => $version] : [];

        $this->deleteQuery('/folders/' . $id, $token, $params, $headers);
    }

    /**
     * Copy the given folder and place it in the given folder.
     *
     * @param int    $id     the id of the folder.
     * @param string $token  the OAuth token.
     * @param string $name   the name of the copy.
     * @param int    $parent the id of the folder to place the copy in.
     * @return array the copied folder.
     */
    public function copy($id, $token, $name, $parent)
    {
        $query = ['name' => $name, 'parent' => ['id' => $parent]];

        return $this->postQuery('/folders/' . $id . '/copy', $token, $query);
    }

    /**
     * Create a shared link to the given folder.
     *
     * @param int    $id           the id of the folder.
     * @param string $token        the OAuth token.
     * @param string $access       the level of access required for this shared link
     * @param string|null $unshared_at  the day that this link should be disabled at
     * @param bool|null   $can_download whether this link allows downloads
     * @param bool|null   $can_preview  whether this link allows previewing
     * @return array the full folder with the updated shared link.
     */
    public function createSharedLink($id, $token, $access, $unshared_at = null, $can_download = null, $can_preview = null)
    {
        $query = ['shared_link' => [
            'access' => $access,
        ]];

        if(! is_null($unshared_at)) $query['shared_link']['unshared_at'] = $unshared_at;

        if(! is_null($can_download)) $query['shared_link']['permissions']['can_download'] = $can_download ? 'true' : 'false';

        if(! is_null($can_preview)) $query['shared_link']['permissions']['can_preview'] = $can_preview ? 'true' : 'false';


        return $this->putQuery('/folders/' . $id, $token, $query);
    }

    /**
     * Delete a shared link to the given folder.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return array the folder.
     */
    public function deleteSharedLink($id, $token)
    {
        return $this->putQuery('/folders/' . $id, $token, ['shared_link' => null]);
    }

    /**
     * Get the collaborations for the given folder.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return array the collaborations.
     */
    public function getCollaborations($id, $token)
    {
        return $this->getQuery('/folders/' . $id . '/collaborations', $token);
    }

    /**
     * Get the items in the trash.
     *
     * @param string   $token  the OAuth token.
     * @param array    $fields attribute(s) to include in the response.
     * @param int|null $limit  the maximum number of items to return.
     * @param int|null $offset the item at which to begin the response.
     * @return array the items in the trash.
     */
    public function getTrash($token, array $fields = [], $limit = null, $offset = null)
    {
        $query = $this->constructQuery(compact('fields', 'limit', 'offset'));

        return $this->getQuery('/folders/trash/items', $token, $query);
    }

    /**
     * Get the given folder that has been trashed.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return array the folder.
     */
    public function getTrashed($id, $token)
    {
        return $this->getQuery('/folders/' . $id . '/trash', $token);
    }

    /**
     * Permanently delete the given folder.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return void
     */
    public function deleteTrashed($id, $token)
    {
        $this->deleteQuery('/folders/' . $id . '/trash', $token);
    }

    /**
     * Restore the given folder from trash.
     *
     * @param int    $id     the id of the folder.
     * @param string $token  the OAuth token.
     * @param string $name   the new name of the folder.
     * @param int    $parent the id of the folder to place the restored folder in.
     * @return array the folder.
     */
    public function restoreTrashed($id, $token, $name, $parent)
    {
        $query = ['name' => $name, 'parent' => ['id' => $parent]];

        return $this->postQuery('/folders/' . $id, $token, $query);
    }

}
