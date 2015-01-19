<?php

namespace Romby\Box\Services;

use Romby\Box\Http\HttpInterface;

class Files extends AbstractService {

    /**
     * THe HTTP interface.
     *
     * @var HttpInterface
     */
    protected $http;

    /**
     * The url to upload to.
     *
     * @var string
     */
    protected $uploadUrl = 'https://upload.box.com/api/2.0/files/content';

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
     * Upload a file to the given name and parent ID.
     *
     * @param string      $token               the OAuth token.
     * @param string      $file                the name of the local file to upload.
     * @param string      $name                the name to store the file under.
     * @param string      $parent              the parent folder to store the file in.
     * @param string|null $content_created_at  the time the file was created.
     * @param string|null $content_modified_at the time the file was last modified.
     * @return array the uploaded file.
     */
    public function upload($token, $file, $name, $parent, $content_created_at = null, $content_modified_at = null)
    {
        $attributes = [
            'name' => $name,
            'parent' => ['id' => $parent],
        ];

        if($content_created_at) $attributes['content_created_at'] = $content_created_at;

        if($content_modified_at) $attributes['content_modified_at'] = $content_modified_at;

        $options = [
            'body' => ['attributes' => json_encode($attributes)]
        ];

        return $this->postQuery($this->uploadUrl, $token, $options, $file);
    }

    /**
     * Get information about a file.
     *
     * @param int    $id    the id of the folder.
     * @param string $token the OAuth token.
     * @return array the file.
     */
    public function get($id, $token)
    {
        return $this->getQuery($this->getFullUrl('/files/' . $id), $token);
    }

    /**
     * Update the given file.
     *
     * @param int         $id      the id of the file.
     * @param string      $token   the OAuth token.
     * @param array       $params  the parameters to set on the folder.
     * @param string|null $version if set, the file will only be updated if this is the latest version.
     * @return array the updated file.
     */
    public function update($id, $token, $params, $version = null)
    {
        $options = [
            'headers' => isset($version) ? ['If-Match' => $version] : [],
            'json' => $params
        ];

        return $this->putQuery($this->getFullUrl('/files/' . $id), $token, $options);
    }

    /**
     * Lock the given file.
     *
     * @param int         $id                    the id of the file.
     * @param string      $token                 the OAuth token.
     * @param string|null $expires_at            the time the lock expires.
     * @param string|null $is_download_prevented true if the file should be prevented from download.
     * @return array the response.
     */
    public function lock($id, $token, $expires_at = null, $is_download_prevented = null)
    {
        $attributes = ['lock' => array_merge(['type' => 'lock'], $this->constructQuery(compact('expires_at', 'is_download_prevented')))];

        $options = [
            'json' => $attributes
        ];

        return $this->putQuery($this->getFullUrl('/files/' . $id), $token, $options);
    }

    /**
     * Unlock the given file.
     *
     * @param int    $id    the id of the file.
     * @param string $token the OAuth token.
     * @return array the response.
     */
    public function unlock($id, $token)
    {
        $options = [
            'json' => ['lock' => null]
        ];

        return $this->putQuery($this->getFullUrl('/files/' . $id), $token, $options);
    }

    /**
     * Download the given file.
     *
     * @param int    $id      the id of the file.
     * @param string $token   the OAuth token.
     * @param string $name    the name to store the file under.
     * @param string $version the specific version of the file to download.
     * @return void
     */
    public function download($id, $token, $name, $version = null)
    {
        $options = [];

        if( ! is_null($version)) $options['query']['version'] = $version;

        $this->downloadQuery($this->getFullUrl('/files/' . $id . '/content'), $token, $options, $name);
    }

    /**
     * Perform a preflight check for an upload.
     *
     * @param string   $token  the OAuth token.
     * @param string   $name   the name to store the file under.
     * @param int      $parent the id of the folder containing the file.
     * @param int|null $size   the size of the file.
     * @return array the response.
     */
    public function preflightCheck($token, $name, $parent, $size = null)
    {
        $options = [
            'json' => [
                'name' => $name,
                'parent' => ['id' => $parent],
            ]
        ];

        if( ! is_null($size)) $options['json']['size'] = $size;

        return $this->optionsQuery($this->getFullUrl('/files/content'), $token, $options);
    }

    /**
     * Delete the given file.
     *
     * @param int         $id      the id of the file to delete.
     * @param string      $token   the OAuth token.
     * @param string|null $version the version to delete.
     * @return void
     */
    public function delete($id, $token, $version = null)
    {
        $options = [];

        if( ! is_null($version)) $options['headers']['If-Match'] = $version;

        $this->deleteQuery($this->getFullUrl('/files/' . $id), $token, $options);
    }

    /**
     * Upload a new version of the given file.
     *
     * @param int         $id      the id of the file to upload a new version of.
     * @param string      $token   the OAuth token.
     * @param string      $file    the local file to upload.
     * @param string|null $version the last known version of the file.
     * @return array the uploaded file.
     */
    public function uploadVersion($id, $token, $file, $version = null)
    {
        $options = [];

        if( ! is_null($version)) $options['headers']['If-Match'] = $version;

        return $this->postQuery('https://upload.box.com/api/2.0/files/' . $id . '/content', $token, $options, $file);
    }

    /**
     * Get the existing versions of the given file.
     *
     * @param int    $id    the id of the file.
     * @param string $token the OAuth token.
     * @return array the versions.
     */
    public function getVersions($id, $token)
    {
        return $this->getQuery($this->getFullUrl('/files/' . $id . '/versions'), $token);
    }

    /**
     * Promote the given version of the given file.
     *
     * @param int    $id      the id of the file.
     * @param string $token   the OAuth token.
     * @param int    $version the version of the file.
     * @return array the new version.
     */
    public function promoteVersion($id, $token, $version)
    {
        $options = [
            'json' => ['type' => 'file_version', 'id' => $version]
        ];

        return $this->postQuery($this->getFullUrl('/files/' . $id . '/versions/current'), $token, $options);
    }

    /**
     * Delete the given version of the file.
     *
     * @param int    $id      the id of the file.
     * @param string $token   the OAuth token.
     * @param int    $version the version of the file.
     * @return void
     */
    public function deleteVersion($id, $token, $version)
    {
        $this->deleteQuery($this->getFullUrl('/files/' . $id . '/versions/' . $version), $token, []);
    }

    /**
     * Copy the given file.
     *
     * @param int      $id     the id of the file.
     * @param string   $token  the OAuth token.
     * @param int|null $parent the id of the folder to put the file in.
     * @param string   $name   the new name of the file.
     * @return array the new file.
     */
    public function copy($id, $token, $parent, $name = null)
    {
        $options = [
            'json' => ['parent' => ['id' => $parent]]
        ];

        if( ! is_null($name)) $options['json']['name'] = $name;

        return $this->postQuery($this->getFullUrl('/files/' . $id . '/copy'), $token, $options);
    }

    /**
     * Create a shared link to the given file.
     *
     * @param int         $id           the id of the file.
     * @param string      $token        the OAuth token.
     * @param string      $access       the level of access required for this shared link.
     * @param string|null $unshared_at  the day that this link should be disabled at.
     * @param bool|null   $can_download whether this link allows downloads.
     * @param bool|null   $can_preview  whether this link allows previewing.
     * @return array the full folder with the updated shared link.
     */
    public function createSharedLink($id, $token, $access, $unshared_at = null, $can_download = null, $can_preview = null)
    {
        $options = [
            'json' => ['shared_link' => ['access' => $access]]
        ];

        if( ! is_null($unshared_at)) $options['json']['shared_link']['unshared_at'] = $unshared_at;

        if( ! is_null($can_download)) $options['json']['shared_link']['permissions']['can_download'] = $can_download ? 'true' : 'false';

        if( ! is_null($can_preview)) $options['json']['shared_link']['permissions']['can_preview'] = $can_preview ? 'true' : 'false';

        return $this->putQuery($this->getFullUrl('/files/' . $id), $token, $options);
    }

    /**
     * Delete a shared link to the given file.
     *
     * @param int    $id    the id of the file.
     * @param string $token the OAuth token.
     * @return array the folder.
     */
    public function deleteSharedLink($id, $token)
    {
        return $this->putQuery($this->getFullUrl('/files/' . $id), $token, ['json' => ['shared_link' => null]]);
    }

}
