## [Tasks](https://developers.box.com/docs/#tasks)

Instantiate the required library class
```php
$http = new \Romby\Box\Http\Adapters\GuzzleHttpAdapter(new \GuzzleHttp\Client())
$tasks = new \Romby\Box\Services\Tasks($http);
```

### [Create a Task](https://developers.box.com/docs/#tasks-create-a-task)
```php
/* @param string      $token   the OAuth token.
 * @param int         $fileId  the file this task is for.
 * @param string|null $message an optional message to include with the task.
 * @param string|null $dueAt   the day at which this task is due.
 * @return array the new task.
 */
$tasks->create($token, $fileId, $message = null, $dueAt = null);
```

### [Get a Task](https://developers.box.com/docs/#tasks-get-a-task)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the ID of the task.
 * @return array the task.
 */
$tasks->get($token, $id);
```

### [Update a Task](https://developers.box.com/docs/#tasks-update-a-task)
```php
/* @param string      $token   the OAuth token.
 * @param int         $id      the id of the task.
 * @param string|null $message an optional message to include with the task.
 * @param string|null $dueAt   the day at which this task is due.
 * @return array the updated task.
 */
$tasks->update($token, $id, $message = null, $dueAt = null);
```

### [Delete a Task](https://developers.box.com/docs/#tasks-delete-a-task)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the id of the task.
 * @return void
 */
$tasks->delete($token, $id);
```

### [Get the Assignments for a Task](https://developers.box.com/docs/#tasks-get-the-assignments-for-a-task)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the id of the task assignment.
 * @return array the task assignment.
 */
$tasks->getTaskAssignment($token, $id);
```

### [Create a Task Assignment](https://developers.box.com/docs/#tasks-create-a-task-assignment)
```php
/* @param string $token    the OAuth token.
 * @param int    $taskId   the id of the task.
 * @param array  $assignTo the user to assign the task to.
 * @return array the new task assignment.
 */
$tasks->createTaskAssignment($token, $taskId, array $assignTo);
```

### [Get a Task Assignment](https://developers.box.com/docs/#tasks-get-a-task-assignment)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the id of the task assignment.
 * @return array the task assignment.
 */
$tasks->getTaskAssignment($token, $id);
```

### [Delete a Task Assignment](https://developers.box.com/docs/#tasks-delete-a-task-assignment)
```php
/* @param string $token the OAuth token.
 * @param int    $id    the id of the task assignment.
 * @return void
 */
$tasks->deleteTaskAssignment($token, $id);
```

### [Update a Task Assignment](https://developers.box.com/docs/#tasks-update-a-task-assignment)
```php
/* @param string $token           the OAuth token.
 * @param int    $id              the id of the task assignment.
 * @param string $message         a message from the assignee about this task
 * @param string $resolutionState can be completed, incomplete, approved, or rejected.
 * @return array the updated task assignment.
 */
$tasks->updateTaskAssignment($token, $id, $message, $resolutionState);
```