<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class SharedItemsSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\SharedItems');
    }

    function it_gets_shared_items($http)
    {
        $http->get('https://api.box.com/2.0/shared_items', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'BoxApi' => 'shared_link=my-shared-link,shared_link_password=secret-password'],
        ])->willReturn('response');

        $this->get('my-secret-token', 'my-shared-link', 'secret-password')->shouldReturn('response');
    }

}
