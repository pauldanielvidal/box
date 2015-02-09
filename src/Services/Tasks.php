<?php namespace Romby\Box\Services;

use Illuminate\Support\Arr;

class Tasks extends AbstractService {

    /**
     * Create a new task assignment.
     *
     * @param string      $token   the OAuth token.
     * @param int         $fileId  the file this task is for.
     * @param string|null $message an optional message to include with the task.
     * @param string|null $dueAt   the day at which this task is due.
     * @return array the new task.
     */
    public function create($token, $fileId, $message = null, $dueAt = null)
    {
        $options = [
            'json' => [
                'item' => ['id' => $fileId, 'type' => 'file'],
                'action' => 'review'
            ]
        ];

        if( ! is_null($message))
        {
            Arr::set($options, 'json.message', $message);
        }

        if( ! is_null($dueAt))
        {
            Arr::set($options, 'json.due_at', $dueAt);
        }

        return $this->postQuery($this->getFullUrl('/tasks'), $token, $options);
    }

    /**
     * Get a task.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the ID of the task.
     * @return array the task.
     */
    public function get($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/tasks/' . $id), $token);
    }

    /**
     * Update a task.
     *
     * @param string      $token   the OAuth token.
     * @param int         $id      the id of the task.
     * @param string|null $message an optional message to include with the task.
     * @param string|null $dueAt   the day at which this task is due.
     * @return array the updated task.
     */
    public function update($token, $id, $message = null, $dueAt = null)
    {
        $options = [];

        if( ! is_null($message))
        {
            Arr::set($options, 'json.message', $message);
        }

        if( ! is_null($dueAt))
        {
            Arr::set($options, 'json.due_at', $dueAt);
        }

        return $this->putQuery($this->getFullUrl('/tasks/' . $id), $token, $options);
    }

    /**
     * Delete a task.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the task.
     * @return void
     */
    public function delete($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/tasks/' . $id), $token);
    }

    /**
     * Create a new task assignment.
     *
     * @param string $token    the OAuth token.
     * @param int    $taskId   the id of the task.
     * @param array  $assignTo the user to assign the task to.
     * @return array the new task assignment.
     */
    public function createTaskAssignment($token, $taskId, array $assignTo)
    {
        $options = [
            'json' => [
                'task' => ['type' => 'task', 'id' => $taskId],
                'assign_to' => $assignTo
            ]
        ];

        return $this->postQuery($this->getFullUrl('/task_assignments'), $token, $options);
    }

    /**
     * Get a task assignment.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the task assignment.
     * @return array the task assignment.
     */
    public function getTaskAssignment($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/task_assignments/' . $id), $token);
    }

    /**
     * Update a task assignment.
     *
     * @param string $token           the OAuth token.
     * @param int    $id              the id of the task assignment.
     * @param string $message         a message from the assignee about this task
     * @param string $resolutionState can be completed, incomplete, approved, or rejected.
     * @return array the updated task assignment.
     */
    public function updateTaskAssignment($token, $id, $message, $resolutionState)
    {
        $options = [];

        if( ! is_null($message))
        {
            Arr::set($options, 'json.message', $message);
        }

        if( ! is_null($options))
        {
            Arr::set($options, 'json.resolution_state', $resolutionState);
        }

        return $this->putQuery($this->getFullUrl('/task_assignments/' . $id), $token, $options);
    }

    /**
     * Delete a task assignment.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the task assignment.
     * @return void
     */
    public function deleteTaskAssignment($token, $id)
    {
        $this->deleteQuery($this->getFullUrl('/task_assignments/' . $id), $token);
    }

    /**
     * Get the assignments for the given task.
     *
     * @param string $token the OAuth token.
     * @param int    $id    the id of the task.
     * @return array the assignments.
     */
    public function getTaskAssignments($token, $id)
    {
        return $this->getQuery($this->getFullUrl('/tasks/' . $id . '/assignments'), $token);
    }

}
