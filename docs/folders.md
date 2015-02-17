## [Folders](https://developers.box.com/docs/#folders)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$folders = new \Romby\Box\Services\Folders($http);
```

### [Get a Folder’s Items](https://developers.box.com/docs/#folders-retrieve-a-folders-items)
```php
/* @param int      $id     the id of the folder.
 * @param string   $token  the OAuth token.
 * @param array    $fields attribute(s) to include in the response
 * @param int|null $limit  the maximum number of items to return in a page.
 * @param int|null $offset the offset at which to begin the response.
 * @return array the items.
 */
$folders->getItems($id, $token, array $fields = [], $limit = null, $offset = null);
```

### [Create a New Folder](https://developers.box.com/docs/#folders-create-a-new-folder)
```php
/* @param string $token  the OAuth token.
 * @param string $name   the name of the new folder.
 * @param int    $parent the ID of the parent of the folder.
 * @return array the new folder.
 */
$folders->create($token, $name, $parent);
```

### [Get Information About a Folder](https://developers.box.com/docs/#folders-get-information-about-a-folder)
```php
/* @param int    $id    the id of the folder.
 * @param string $token the OAuth token.
 * @return array the folder.
 */
$folders->get($id, $token);
```

### [Update Information About a Folder](https://developers.box.com/docs/#folders-update-information-about-a-folder)
```php
/* @param int         $id      the id of the folder.
 * @param string      $token   the OAuth token.
 * @param array       $params  the parameters to set on the folder.
 * @param string|null $version if set, the folder will only be updated if this is the latest version.
 * @return array the updated folder.
 */
$folders->update($id, $token, $params, $version = null);
```

### [Delete a Folder](https://developers.box.com/docs/#folders-delete-a-folder)
```php
/* @param int    $id        the id of the folder.
 * @param string $token     the OAuth token.
 * @param array  $fields    attribute(s) to include in the response.
 * @param null   $recursive whether to delete this folder if it has items inside of it.
 * @param null   $version
 * @return void
 */
$folders->delete($id, $token, $fields = [], $recursive = null, $version = null);
```

### [Copy a Folder](https://developers.box.com/docs/#folders-copy-a-folder)
```php
/* @param int    $id     the id of the folder.
 * @param string $token  the OAuth token.
 * @param string $name   the name of the copy.
 * @param int    $parent the id of the folder to place the copy in.
 * @return array the copied folder.
 */
$folders->copy($id, $token, $name, $parent);
```

### [Create a Shared Link for a Folder](https://developers.box.com/docs/#folders-create-a-shared-link-for-a-folder)
```php
/* @param int         $id           the id of the item.
 * @param string      $token        the OAuth token.
 * @param string      $access       the level of access required for this shared link.
 * @param string|null $unshared_at  the day that this link should be disabled at.
 * @param bool|null   $can_download whether this link allows downloads.
 * @param bool|null   $can_preview  whether this link allows previewing.
 * @return array the full folder with the updated shared link.
 */
$folders->createSharedLink($id, $token, $access, $unshared_at = null, $can_download = null, $can_preview = null);
```

### [View a Folder’s Collaborations](https://developers.box.com/docs/#folders-view-a-folders-collaborations)
```php
/* @param int    $id    the id of the folder.
 * @param string $token the OAuth token.
 * @return array the collaborations.
 */
$folders->getCollaborations($id, $token);
```

### [Get the Items in the Trash](https://developers.box.com/docs/#folders-get-the-items-in-the-trash)
```php
/* @param string   $token  the OAuth token.
 * @param array    $fields attribute(s) to include in the response.
 * @param int|null $limit  the maximum number of items to return.
 * @param int|null $offset the item at which to begin the response.
 * @return array the items in the trash.
 */
$folders->getTrash($token, array $fields = [], $limit = null, $offset = null);
```

### [Get a Trashed Folder](https://developers.box.com/docs/#folders-get-a-trashed-folder)
```php
/* @param int    $id    the id of the item.
 * @param string $token the OAuth token.
 * @return array the folder.
 */
$folders->getTrashed($id, $token);
```

### [Permanently Delete a Trashed Folder](https://developers.box.com/docs/#folders-permanently-delete-a-trashed-folder)
```php
/* @param int    $id    the id of the item.
 * @param string $token the OAuth token.
 * @return void
 */
$folders->deleteTrashed($id, $token);
```

### [Restore a Trashed Folder](https://developers.box.com/docs/#folders-permanently-delete-a-trashed-folder)
```php
/* @param int    $id     the id of the item.
 * @param string $token  the OAuth token.
 * @param string $name   the new name of the item.
 * @param int    $parent the id of the folder to place the restored folder in.
 * @return array the folder.
 */
$folders->restoreTrashed($id, $token, $name, $parent);
```