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
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the group.
 * @return array group membership entries.
 */
$groups->getMembershipList($token, $id);
```

### [Get all Group Memberships for a User](https://developers.box.com/docs/#groups-get-all-group-memberships-for-a-user)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the user.
 * @return array group membership entries.
 */
$groups->getUsersGroups($token, $id);
```

### [Get a Group Membership Entry](https://developers.box.com/docs/#groups-get-a-group-membership-entry)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the group membership entry.
 * @return array the group membership entry.
 */
$groups->getMembershipEntry($token, $id);
```

### [Add a Member to a Group](https://developers.box.com/docs/#groups-add-a-member-to-a-group)
```php
/* @param string      $token   the OAuth token.
 * @param int         $userId  the ID of the user.
 * @param int         $groupId the ID of the group.
 * @param string|null $role    the role of the member.
 * @return array group membership entry.
 */
$groups->addUserToGroup($token, $userId, $groupId, $role = null);
```

### [Update a Group Membership](https://developers.box.com/docs/#groups-update-a-group-membership)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the membership entry.
 * @param string $role  the role of the user.
 * @return array group membership entry.
 */
$groups->updateMembershipEntry($token, $id, $role);
```

### [Delete a Group Membership](https://developers.box.com/docs/#groups-delete-a-group-membership)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the user.
 * @return void.
 */
$groups->deleteMembershipEntry($token, $id);
```

### [Get All Collaborations for a Group](https://developers.box.com/docs/#get-all-collaborations-for-a-group)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the group.
 * @return array collaborations.
 */
$groups->getAllCollaborations($token, $id);
```