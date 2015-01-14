<?php

namespace Romby\Box\Services;

use Romby\Box\Http\HttpInterface;

class Files extends AbstractService {

    protected $http;

    protected $uploadUrl = 'https://upload.box.com/api/2.0/files/content';

    public function __construct(HttpInterface $http)
    {
        $this->http = $http;
    }

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

    public function get($id, $token)
    {
        return $this->getQuery($this->getFullUrl('/files/'.$id), $token);
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

        return $this->putQuery($this->getFullUrl('/files/'.$id), $token, $options);
    }

    public function lock($id, $token, $expires_at = null, $is_download_prevented = null)
    {
        $attributes = ['lock' => array_merge(['type' => 'lock'], compact('expires_at', 'is_download_prevented'))];

        $options = [
            'json' => $this->constructQuery($attributes)
        ];

        return $this->putQuery($this->getFullUrl('/files/'.$id), $token, $options);
    }

    public function unlock($id, $token)
    {
        $options = [
            'json' => ['lock' => null]
        ];

        return $this->putQuery($this->getFullUrl('/files/'.$id), $token, $options);
    }

}
