<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class CollaborationsSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Collaborations');
    }

    function it_creates_new_collaborations($http)
    {
        $http->post('https://api.box.com/2.0/collaborations', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['item' => ['id' => 0, 'type' => 'folder'], 'accessible_by' => ['id' => 100, 'type' => 'user'], 'role' => 'editor']
        ], null)->willReturn('response');

        $this->create('my-secret-token', 0, 'editor', 100, 'user')->shouldReturn('response');
    }

    function it_creates_new_collaborations_for_external_collaborators($http)
    {
        $http->post('https://api.box.com/2.0/collaborations', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['item' => ['id' => 0, 'type' => 'folder'], 'accessible_by' => ['login' => 'johndoe@example.com'], 'role' => 'editor']
        ], null)->willReturn('response');

        $this->create('my-secret-token', 0, 'editor', null, null, 'johndoe@example.com')->shouldReturn('response');
    }

    function it_retrieves_collaborations($http)
    {
        $http->get('https://api.box.com/2.0/collaborations/1564', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['fields' => 'id,status']
        ])->willReturn('response');

        $this->get('my-secret-token', 1564, ['id', 'status'])->shouldReturn('response');
    }

    function it_updates_collaborations($http)
    {
        $http->put('https://api.box.com/2.0/collaborations/1328', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['role' => 'viewer', 'status' => 'rejected']
        ])->willReturn('response');

        $this->update('my-secret-token', 1328, 'viewer', 'rejected')->shouldReturn('response');
    }

    function it_deletes_collaborations($http)
    {
        $http->delete('https://api.box.com/2.0/collaborations/1823', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 1823);
    }

    function it_gets_pending_collaborations_for_the_current_user($http)
    {
        $http->get('https://api.box.com/2.0/collaborations', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => ['status' => 'pending']
        ])->willReturn('response');

        $this->getPending('my-secret-token')->shouldReturn('response');
    }

}
