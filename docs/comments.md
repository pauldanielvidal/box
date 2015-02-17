## [Comments](https://developers.box.com/docs/#comments)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$comments = new \Romby\Box\Services\Comments($http);
```

### [Add a Comment to an Item](https://developers.box.com/docs/#comments)
```php
/* @param string      $token         the OAuth token.
 * @param int         $id            the id of the item to comment.
 * @param string      $type          the type of the item to comment.
 * @param string      $message       the comment message.
 * @param string|null $taggedMessage the comment message, with tags.
 * @return array the response.
 */
$comments->create($token, $id, $type, $message, $taggedMessage = null);
```

### [Change a Comment’s Message](https://developers.box.com/docs/#comments-change-a-comments-message)
```php
/* @param int    $id      the id of the comment.
 * @param string $token   the OAuth token.
 * @param string $message the new message.
 * @return array the response.
 */
$comments->update($id, $token, $message);
```

### [Get Information About a Comment](https://developers.box.com/docs/#comments-get-information-about-a-comment)
```php
/* @param int    $id    the id of the comment.
 * @param string $token the OAuth token.
 * @return array the response.
 */
$comments->get($id, $token);
```

### [Delete a Comment](https://developers.box.com/docs/#comments-delete-a-comment)
```php
/* @param int    $id    the id of the comment.
 * @param string $token the OAuth token.
 * @return void
 */
$comments->delete($id, $token);
```