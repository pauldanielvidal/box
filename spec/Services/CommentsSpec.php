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
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['item' => ['id' => 0, 'type' => 'bar'], 'message' => 'message']
        ], null)->willReturn('response');

        $this->create('foo', 0, 'bar', 'message')->shouldReturn('response');
    }

}
