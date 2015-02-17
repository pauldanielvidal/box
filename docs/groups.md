## [Groups](https://developers.box.com/docs/#groups)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$groups = new \Romby\Box\Services\Groups($http);
```

### [Get all Groups](https://developers.box.com/docs/#groups-get-all-groups)
```php
/* @param string $token the OAuth token.
 * @return array the groups.
 */
$groups->all($token);
```

### [Create a Group](https://developers.box.com/docs/#groups-create-a-group)
```php
/* @param string $token the OAuth token.
 * @param string $name  the name of the group.
 * @return array the new group.
 */
$groups->create($token, $name);
```

### [Update a Group](https://developers.box.com/docs/#update-a-group)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the group.
 * @param string $name  the new name of the group.
 * @return array the updated group.
 */
$groups->update($token, $id, $name);
```

### [Delete a Group](https://developers.box.com/docs/#delete-a-group)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the group.
 * @return void
 */
$groups->delete($token, $id);
```

### [Get the Membership list for a Group](https://developers.box.com/docs/#groups-get-the-membership-list-for-a-group)
```php
/* Not Implemented */
```

### [Get all Group Memberships for a User](https://developers.box.com/docs/#groups-get-all-group-memberships-for-a-user)
```php
/* Not Implemented */
```

### [Get a Group Membership Entry](https://developers.box.com/docs/#groups-get-a-group-membership-entry)
```php
/* Not Implemented */
```

### [Add a Member to a Group](https://developers.box.com/docs/#groups-add-a-member-to-a-group)
```php
/* Not Implemented */
```

### [Update a Group Membership](https://developers.box.com/docs/#groups-update-a-group-membership)
```php
/* Not Implemented */
```

### [Delete a Group Membership](https://developers.box.com/docs/#groups-delete-a-group-membership)
```php
/* Not Implemented */
```

### [Get All Collaborations for a Group](https://developers.box.com/docs/#get-all-collaborations-for-a-group)
```php
/* Not Implemented */
```