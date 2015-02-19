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

    function it_gets_the_membership_list_for_a_group($http)
    {
        $http->get('https://api.box.com/2.0/groups/543/memberships', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getMembershipList('my-secret-token', 543)->shouldReturn('response');
    }

    function it_gets_all_group_memberships_for_a_user($http)
    {
        $http->get('https://api.box.com/2.0/users/2314/memberships', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getUsersGroups('my-secret-token', 2314)->shouldReturn('response');
    }

    function it_gets_a_group_membership_entry($http)
    {
        $http->get('https://api.box.com/2.0/group_memberships/1560354', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getMembershipEntry('my-secret-token', 1560354)->shouldReturn('response');
    }

    function it_adds_user_to_group($http)
    {
        $http->post('https://api.box.com/2.0/group_memberships', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['user' => ['id' => 2314], 'group' => ['id' => 543]]
        ], null)->willReturn('response');

        $this->addUserToGroup('my-secret-token', 2314, 543)->shouldReturn('response');
    }

    function it_adds_user_to_group_as_admin($http)
    {
        $http->post('https://api.box.com/2.0/group_memberships', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['user' => ['id' => 2314], 'group' => ['id' => 543], 'role' => 'admin']
        ], null)->willReturn('response');

        $this->addUserToGroup('my-secret-token', 2314, 543, 'admin')->shouldReturn('response');
    }

    function it_updates_membership_entry($http)
    {
        $http->put('https://api.box.com/2.0/group_memberships/1560354', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['role' => 'member']
        ])->willReturn('response');

        $this->updateMembershipEntry('my-secret-token', 1560354, 'member')->shouldReturn('response');        
    }

    function it_deletes_membership_entry($http)
    {
        $http->delete('https://api.box.com/2.0/group_memberships/1560354', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->deleteMembershipEntry('my-secret-token', 1560354);
    }

    function it_gets_all_collaborations_for_group($http)
    {
        $http->get('https://api.box.com/2.0/groups/543/collaborations', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getAllCollaborations('my-secret-token', 543)->shouldReturn('response');
    }

}
