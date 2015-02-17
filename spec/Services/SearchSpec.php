<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class SearchSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Search');
    }

    function it_searches_for_stuff($http)
    {
        $http->get('https://api.box.com/2.0/search', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => ['query' => 'Search Query', 'file_extensions' => 'pdf']
        ])->willReturn('response');

        $this->query('my-secret-token', 'Search Query', ['file_extensions' => 'pdf'])->shouldReturn('response');

    }
}
