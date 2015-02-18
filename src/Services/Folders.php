<?php namespace Romby\Box\Services;

use Romby\Box\Services\Common\SharedLink;
use Romby\Box\Services\Common\TrashedItems;

class Folders extends AbstractService {

    use SharedLink, TrashedItems;

    /**
     * The base service url.
     *
     * @var string
     */
    protected $baseServiceUrl = '/folders/';

    /**
     * Get the items in a folder.
     *
     * @param string   $token  the OAuth token.
     * @param int      $id     the id of the folder.
     * @param array    $fields attribute(s) to include in the response
     * @param int|null $limit  the maximum number of items to return in a page.
     * @param int|null $offset the offset at which to begin the response.
     * @return array the items.
     */
    public function getItems($token, $id, array $fields = [], $limit = null, $offset = null)
    {
        $options = [
            'query' => $this->constructQuery(compact('fields', 'limit', 'offset'))
        ];

        return $this->getQuery($this->getFullUrl('/folders/' . $id . '/items'), $token, $options);
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
        $options = [
            'json' => ['name' => $name, 'parent' => ['id' => $parent]]
        ];

        return $this->postQuery($this->getFullUrl('/folders'), $token, $options);
    }

    /**
     * Get the given folder.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the folder.
     * @return array the folder.
     */
    public function get($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/folders/' . $id), $token);
    }

    /**
     * Update the given folder.
     *
     * @param string      $token   the OAuth token.
     * @param int         $id      the id of the folder.
     * @param array       $params  the parameters to set on the folder.
     * @param string|null $version if set, the folder will only be updated if this is the latest version.
     * @return array the updated folder.
     */
    public function update($token, $id, $params, $version = null)
    {
        $options = [
            'headers' => isset($version) ? ['If-Match' => $version] : [],
            'json' => $params
        ];

        return $this->putQuery($this->getFullUrl('/folders/' . $id), $token, $options);
    }

    /**
     * Delete the given folder.
     *
     * @param string $token     the OAuth token.
     * @param int    $id        the id of the folder.
     * @param array  $fields    attribute(s) to include in the response.
     * @param null   $recursive whether to delete this folder if it has items inside of it.
     * @param null   $version
     * @return void
     */
    public function delete($token, $id, $fields = [], $recursive = null, $version = null)
    {
        $options = [
            'query' => $this->constructQuery(compact('fields', 'recursive')),
            'headers' => isset($version) ? ['If-Match' => $version] : []
        ];

        $this->deleteQuery($this->getFullUrl('/folders/' . $id), $token, $options);
    }

    /**
     * Copy the given folder and place it in the given folder.
     *
     * @param string $token  the OAuth token.
     * @param int    $id     the id of the folder.
     * @param string $name   the name of the copy.
     * @param int    $parent the id of the folder to place the copy in.
     * @return array the copied folder.
     */
    public function copy($token, $id, $name, $parent)
    {
        $options = [
            'json' => ['name' => $name, 'parent' => ['id' => $parent]]
        ];

        return $this->postQuery($this->getFullUrl('/folders/' . $id . '/copy'), $token, $options);
    }

    /**
     * Get the collaborations for the given folder.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the folder.
     * @return array the collaborations.
     */
    public function getCollaborations($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/folders/' . $id . '/collaborations'), $token);
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
        $options = ['query' => $this->constructQuery(compact('fields', 'limit', 'offset'))];

        return $this->getQuery($this->getFullUrl('/folders/trash/items'), $token, $options);
    }

    /**
     * Get the base service url.
     *
     * @return string the base service url.
     */
    protected function getBaseServiceUrl()
    {
        return $this->baseServiceUrl;
    }

}
