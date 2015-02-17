## [Files](https://developers.box.com/docs/#files)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$files = new \Romby\Box\Services\Files($http);
```

### [Get Information About a File](https://developers.box.com/docs/#files-get)
```php
/* @param int    $id    the id of the folder.
 * @param string $token the OAuth token.
 * @return array the file.
 */
$files->get($id, $token)
```

### [Update a file’s information](https://developers.box.com/docs/#files-update-a-files-information)
```php
/* @param int         $id      the id of the file.
 * @param string      $token   the OAuth token.
 * @param array       $params  the parameters to set on the folder.
 * @param string|null $version if set, the file will only be updated if this is the latest version.
 * @return array the updated file.
 */
$files->update($id, $token, $params, $version = null);
```

### [Lock and Unlock](https://developers.box.com/docs/#files-lock-and-unlock)
To Lock:
```php
/* @param int         $id                    the id of the file.
 * @param string      $token                 the OAuth token.
 * @param string|null $expires_at            the time the lock expires.
 * @param string|null $is_download_prevented true if the file should be prevented from download.
 * @return array the response.
 */
$files->lock($id, $token, $expires_at = null, $is_download_prevented = null);
```

To Unlock:
```php
/* @param int    $id    the id of the file.
 * @param string $token the OAuth token.
 * @return array the response.
 */
$files->unlock($id, $token);
```

### [Download a File](https://developers.box.com/docs/#files-download-a-file)
```php
/* @param int    $id      the id of the file.
 * @param string $token   the OAuth token.
 * @param string $name    the name to store the file under.
 * @param string $version the specific version of the file to download.
 * @return void
 */
$files->download($id, $token, $name, $version = null);
```

### [Preflight Check](https://developers.box.com/docs/#files-preflight-check)
```
/* @param string   $token  the OAuth token.
 * @param string   $name   the name to store the file under.
 * @param int      $parent the id of the folder containing the file.
 * @param int|null $size   the size of the file.
 * @return array the response.
 */
$files->preflightCheck($token, $name, $parent, $size = null);
```

### [Upload a File](https://developers.box.com/docs/#files-upload-a-file)
```php
/* @param string      $token               the OAuth token.
 * @param string      $file                the name of the local file to upload.
 * @param string      $name                the name to store the file under.
 * @param string      $parent              the parent folder to store the file in.
 * @param string|null $content_created_at  the time the file was created.
 * @param string|null $content_modified_at the time the file was last modified.
 * @return array the uploaded file.
 */
$files->upload($token, $file, $name, $parent, $content_created_at = null, $content_modified_at = null);
```

### [Delete a file](https://developers.box.com/docs/#files-delete-a-file)
```php
/* @param int         $id      the id of the file to delete.
 * @param string      $token   the OAuth token.
 * @param string|null $version the version to delete.
 * @return void
 */
$files->delete($id, $token, $version = null);
```

### [Upload a New Version of a File](https://developers.box.com/docs/#files-upload-a-new-version-of-a-file)
```php
/* @param int         $id      the id of the file to upload a new version of.
 * @param string      $token   the OAuth token.
 * @param string      $file    the local file to upload.
 * @param string|null $version the last known version of the file.
 * @return array the uploaded file.
 */
$files->uploadVersion($id, $token, $file, $version = null);
```

### [View Versions of a File](https://developers.box.com/docs/#files-view-versions-of-a-file)
```php
/* @param int    $id    the id of the file.
 * @param string $token the OAuth token.
 * @return array the versions.
 */
$files->getVersions($id, $token);
```

### [Download an Old Version of a File](https://developers.box.com/docs/#files-download-old-version)
See "Download a File"

### [Promote an Old Version of a File](https://developers.box.com/docs/#files-promote-old-version)
```php
/* @param int    $id      the id of the file.
 * @param string $token   the OAuth token.
 * @param int    $version the version of the file.
 * @return array the new version.
 */
$files->promoteVersion($id, $token, $version);
```

### [Delete an Old Version of a File](https://developers.box.com/docs/#files-delete-version)
```php
/* @param int    $id      the id of the file.
 * @param string $token   the OAuth token.
 * @param int    $version the version of the file.
 * @return void
 */
$files->deleteVersion($id, $token, $version);
```

### [Copy a File](https://developers.box.com/docs/#files-copy-a-file)
```php
/* @param int      $id     the id of the file.
 * @param string   $token  the OAuth token.
 * @param int|null $parent the id of the folder to put the file in.
 * @param string   $name   the new name of the file.
 * @return array the new file.
 */
$files->copy($id, $token, $parent, $name = null);
```

### [Get a Thumbnail for a File](https://developers.box.com/docs/#files-get-a-thumbnail-for-a-file)
```php
/* Not Implemented */
```

### [Create a Shared Link for a File](https://developers.box.com/docs/#files-create-a-shared-link-for-a-file)
```php
/* @param int         $id           the id of the item.
 * @param string      $token        the OAuth token.
 * @param string      $access       the level of access required for this shared link.
 * @param string|null $unshared_at  the day that this link should be disabled at.
 * @param bool|null   $can_download whether this link allows downloads.
 * @param bool|null   $can_preview  whether this link allows previewing.
 * @return array the full folder with the updated shared link.
 */
$files->createSharedLink($id, $token, $access, $unshared_at = null, $can_download = null, $can_preview = null);
```

### [Get a Trashed File](https://developers.box.com/docs/#files-get-a-trashed-file)
```php
/* @param string   $token  the OAuth token.
 * @param array    $fields attribute(s) to include in the response.
 * @param int|null $limit  the maximum number of items to return.
 * @param int|null $offset the item at which to begin the response.
 * @return array the items in the trash.
 */
$files->getTrashed($id, $token);
```

### [Permanently Delete a Trashed File](https://developers.box.com/docs/#files-permanently-delete-a-trashed-file)
```php
/* @param int    $id    the id of the item.
 * @param string $token the OAuth token.
 * @return void
 */
$files->deleteTrashed($id, $token);
```

### [Restore a Trashed Item](https://developers.box.com/docs/#files-restore-a-trashed-item)
```php
/* @param int    $id     the id of the item.
 * @param string $token  the OAuth token.
 * @param string $name   the new name of the item.
 * @param int    $parent the id of the file to place the restored folder in.
 * @return array the folder.
 */
$files->restoreTrashed($id, $token, $name, $parent);
```

### [View the Comments on a File](https://developers.box.com/docs/#files-view-the-comments-on-a-file)
```php
/* @param int    $id     the id of the file.
 * @param string $token  the OAuth token.
 * @param array  $fields the fields to include in the response.
 * @return array the response.
 */
$files->getComments($id, $token, $fields = []);
```

### [Get the tasks for a file](https://developers.box.com/docs/#files-get-the-tasks-for-a-file)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the file.
 * @return array the tasks.
 */
$files->getTasks($token, $id);
```