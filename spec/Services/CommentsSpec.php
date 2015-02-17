<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class CommentsSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Comments');
    }

    function it_posts_new_comments($http)
    {
        $http->post('https://api.box.com/2.0/comments/', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['item' => ['id' => 0, 'type' => 'bar'], 'message' => 'message']
        ], null)->willReturn('response');

        $this->create('my-secret-token', 0, 'bar', 'message')->shouldReturn('response');
    }

    function it_gets_information_about_a_comment($http)
    {
        $http->get('https://api.box.com/2.0/comments/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->get('my-secret-token', 0)->shouldReturn('response');
    }

    function it_updates_the_message_of_existing_comments($http)
    {
        $http->put('https://api.box.com/2.0/comments/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['message' => 'bar']
        ])->willReturn('response');

        $this->update('my-secret-token', 0, 'bar')->shouldReturn('response');
    }

    function it_deletes_comments($http)
    {
        $http->delete('https://api.box.com/2.0/comments/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 0);
    }

}
