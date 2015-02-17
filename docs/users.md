## [Users](https://developers.box.com/docs/#users)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$users = new \Romby\Box\Services\Users($http);
```

### [Get the Current User’s Information](https://developers.box.com/docs/#users-get-the-current-users-information)
```php
/* @param string $token the OAuth Token.
 * @return array the user.
 */
public function me($token);
```

### [As-User](https://developers.box.com/docs/#users-as-user)
```php
/* Not Implemented */
```

### [Get All Users in an Enterprise](https://developers.box.com/docs/#users-get-all-the-users-in-an-enterprise)
```php
/* @param string $token the OAuth Token.
 * @return array the users.
 */
public function all($token);
```

### [Create an Enterprise User](https://developers.box.com/docs/#users-create-an-enterprise-user)
```php
/* @param string $token      the OAuth Token.
 * @param string $login      the email address this user uses to login
 * @param string $name       the name of this user.
 * @param array  $properties the properties of the user.
 * @return array the new user.
 */
public function create($token, $login, $name, $properties = []);
```

### [Get a User’s Information](https://developers.box.com/docs/#users-get-a-users-information)
```php
/* @param string $token the OAuth Token.
 * @param int    $id    the ID of the user.
 * @return array the user's information.
 */
public function get($token, $id);
```

### [Update a User’s Information](https://developers.box.com/docs/#users-get-a-users-information)
```php
/* @param string $token      the OAuth Token.
 * @param int    $id         the ID of the user.
 * @param array  $properties the properties to update.
 * @return array the updated user.
 */
public function update($token, $id, $properties);
```

### [Delete an Enterprise User](https://developers.box.com/docs/#users-delete-an-enterprise-user)
```php
/* @param string $token the OAuth Token.
 * @param int    $id    the ID of the user.
 * @return void
 */
public function delete($token, $id);
```

### [Invite Existing User to Join Enterprise](https://developers.box.com/docs/#users-invite-existing-user-to-join-enterprise)
```php
/* Not Implemented */
```

### [Move Folder into Another User’s Folder](https://developers.box.com/docs/#users-move-folder-into-another-folder)
```php
/* Not Implemented */
```

### [Changing a User’s Primary Login](https://developers.box.com/docs/#users-changing-a-users-primary-login)
```php
/* Not Implemented */
```

### [Get All Email Aliases for a User](https://developers.box.com/docs/#users-get-all-email-aliases-for-a-user)
```php
/* @param string $token the OAuth Token.
 * @param int    $id    the ID of the user.
 * @return array the email aliases.
 */
public function getAllEmailAliases($token, $id);
```

### [Add an Email Alias for a User](https://developers.box.com/docs/#users-add-an-email-alias-for-a-user)
```php
/* @param string $token the OAuth Token.
 * @param int    $id    the ID of the user.
 * @param string $alias the email alias.
 * @return array the response.
 */
public function createEmailAlias($token, $id, $alias);
```

### [Remove an Email Alias from a User](https://developers.box.com/docs/#users-remove-an-email-alias-from-a-user)
```php
/* Not Implemented */
```