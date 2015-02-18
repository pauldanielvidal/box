<?php namespace Romby\Box\Services\Common;

trait SharedLink {

    /**
     * Create a shared link to the given item.
     *
     * @param string      $token        the OAuth token.
     * @param int         $itemId           the id of the item.
     * @param string      $access       the level of access required for this shared link.
     * @param string|null $unshared_at  the day that this link should be disabled at.
     * @param bool|null   $can_download whether this link allows downloads.
     * @param bool|null   $can_preview  whether this link allows previewing.
     * @return array the full folder with the updated shared link.
     */
    public function createSharedLink($token, $itemId, $access, $unshared_at = null, $can_download = null, $can_preview = null)
    {
        $options = [
            'json' => ['shared_link' => ['access' => $access]]
        ];

        if( ! is_null($unshared_at)) $options['json']['shared_link']['unshared_at'] = $unshared_at;

        if( ! is_null($can_download)) $options['json']['shared_link']['permissions']['can_download'] = $can_download ? 'true' : 'false';

        if( ! is_null($can_preview)) $options['json']['shared_link']['permissions']['can_preview'] = $can_preview ? 'true' : 'false';

        return $this->putQuery($this->getFullUrl($this->getBaseServiceUrl() . $itemId), $token, $options);
    }

    /**
     * Delete a shared link to the given item.
     *
     * @param string $token the OAuth token.
     * @param int    $itemId    the id of the item.
     * @return array the folder.
     */
    public function deleteSharedLink($token, $itemId)
    {
        return $this->putQuery($this->getFullUrl($this->getBaseServiceUrl() . $itemId), $token, ['json' => ['shared_link' => null]]);
    }

    /**
     * Perform a PUT query to the given url.
     *
     * @param string $url     the url to send the query to.
     * @param string $token   the OAuth token.
     * @param array  $options the options to send with the request.
     * @return array the response to the query.
     */
    abstract protected function putQuery($url, $token, $options = []);

    /**
     * Get the full url for the given path.
     *
     * @param string $path the path.
     * @return string the full url.
     */
    abstract protected function getFullUrl($path);

    /**
     * Get the base service url.
     *
     * @return string the base service url.
     */
    abstract protected function getBaseServiceUrl();

}
