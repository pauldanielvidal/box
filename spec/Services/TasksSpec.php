<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class TasksSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Tasks');
    }

    function it_creates_tasks($http)
    {
        $http->post('https://api.box.com/2.0/tasks', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['item' => ['type' => 'file', 'id' => 653], 'action' => 'review', 'message' => 'Review this file', 'due_at' => '2014-04-03T11:09:43-07:00']
        ], null)->willReturn('response');

        $this->create('my-secret-token', 653, 'Review this file', '2014-04-03T11:09:43-07:00')->shouldReturn('response');
    }

    function it_gets_tasks($http)
    {
        $http->get('https://api.box.com/2.0/tasks/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->get('my-secret-token', 336)->shouldReturn('response');
    }

    function it_updates_tasks($http)
    {
        $http->put('https://api.box.com/2.0/tasks/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['message' => 'Updated message', 'due_at' => '2014-04-10T11:09:43-07:00']
        ])->willReturn('response');

        $this->update('my-secret-token', 336, 'Updated message', '2014-04-10T11:09:43-07:00')->shouldReturn('response');
    }

    function it_deletes_tasks($http)
    {
        $http->delete('https://api.box.com/2.0/tasks/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 336);
    }

    function it_creates_task_assignments($http)
    {
        $http->post('https://api.box.com/2.0/task_assignments', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['task' => ['type' => 'task', 'id' => 653], 'assign_to' => ['id' => 115]]
        ], null)->willReturn('response');

        $this->createTaskAssignment('my-secret-token', 653, ['id' => 115])->shouldReturn('response');
    }

    function it_gets_task_assignments($http)
    {
        $http->get('https://api.box.com/2.0/task_assignments/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getTaskAssignment('my-secret-token', 336)->shouldReturn('response');
    }

    function it_updates_task_assignments($http)
    {
        $http->put('https://api.box.com/2.0/task_assignments/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['message' => 'Updated message', 'resolution_state' => 'completed']
        ])->willReturn('response');

        $this->updateTaskAssignment('my-secret-token', 336, 'Updated message', 'completed')->shouldReturn('response');
    }

    function it_deletes_task_assignments($http)
    {
        $http->delete('https://api.box.com/2.0/task_assignments/336', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->deleteTaskAssignment('my-secret-token', 336);
    }

    function it_gets_the_assignments_for_a_task($http)
    {
        $http->get('https://api.box.com/2.0/tasks/336/assignments', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getTaskAssignments('my-secret-token', 336)->shouldReturn('response');

    }
}
