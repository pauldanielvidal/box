## [Collaborations](https://developers.box.com/docs/#collaborations)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$collaborations = new \Romby\Box\Services\Collaborations($http);
```

### [Add a Collaboration](https://developers.box.com/docs/#collaborations-add-a-collaboration)
```php
/* @param string      $token             the OAuth token.
 * @param int         $id                the id of the folder to create a collaboration for.
 * @param string      $role              the role to assign to the collaborator.
 * @param int|null    $collaboratorId    the id of the collaborator.
 * @param string|null $collaboratorType  the type of the collaborator, either user or group.
 * @param string|null $collaboratorLogin an email address (does not need to be a Box user). Omit if this is a
 *                                       group, or if you include the user ID.
 * @return array
 */
$collaborations->create($token, $id, $role, $collaboratorId = null, $collaboratorType = null, $collaboratorLogin = null);
```

### [Edit a Collaboration](https://developers.box.com/docs/#collaborations-edit-a-collaboration)
```php
/* @param int         $id     the id of the collaboration.
 * @param string      $token  the OAuth token.
 * @param string|null $role   the new role of the collaborator.
 * @param string|null $status the new status of the collaboration.
 * @return array the response.
 */
$collaborations->update($id, $token, $role = null, $status = null);
```

### [Remove a Collaboration](https://developers.box.com/docs/#collaborations-remove-a-collaboration)
```php
/* @param int    $id    the id of the collaboration.
 * @param string $token the OAuth token.
 * @return void
 */
$collaborations->delete($id, $token);
```

### [Retrieve a Collaboration](https://developers.box.com/docs/#collaborations-retrieve-a-collaboration)
```php
/* @param int    $id     the id of the collaboration.
 * @param string $token  the OAuth token.
 * @param array  $fields the fields to include in the response.
 * @return array the response.
 */
$collaborations->get($id, $token, $fields = []);
```

### [Get Pending Collaborations](https://developers.box.com/docs/#collaborations-get-pending-collaborations)
```php
/* @param string $token the OAuth token.
 * @return array the response.
 */
$collaborations->getPending($token);
```