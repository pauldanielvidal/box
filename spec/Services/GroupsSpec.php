<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class GroupsSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Groups');
    }

    function it_creates_groups($http)
    {
        $http->post('https://api.box.com/2.0/groups', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'New Group']
        ], null)->willReturn('response');

        $this->create('my-secret-token', 'New Group')->shouldReturn('response');
    }

    function it_gets_specific_groups($http)
    {
        $http->get('https://api.box.com/2.0/groups/543', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->get('my-secret-token', 543)->shouldReturn('response');
    }

    function it_gets_all_groups($http)
    {
        $http->get('https://api.box.com/2.0/groups', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->all('my-secret-token')->shouldReturn('response');
    }

    function it_updates_groups($http)
    {
        $http->put('https://api.box.com/2.0/groups/543', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'Updated Group']
        ])->willReturn('response');

        $this->update('my-secret-token', 543, 'Updated Group')->shouldReturn('response');
    }

    function it_deletes_groups($http)
    {
        $http->delete('https://api.box.com/2.0/groups/543', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 543);
    }

}
