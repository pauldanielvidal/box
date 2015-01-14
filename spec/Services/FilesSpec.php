<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class FilesSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Files');
    }

    function it_gets_information_about_files($http)
    {
        $http->get('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
        ])->willReturn('response');

        $this->get(0, 'foo')->shouldReturn('response');
    }

    function it_uploads_files($http)
    {
        $http->post('https://upload.box.com/api/2.0/files/content', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'body' => [
                'attributes' => json_encode(['name' => 'baz', 'parent' => ['id' => 0]]),
            ]
        ], 'bar')->willReturn('response');

        $this->upload('foo', 'bar', 'baz', 0)->shouldReturn('response');
    }

    function it_updates_information_about_a_file($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update(0, 'foo', ['bar' => 'baz'])->shouldReturn('response');
    }

    function it_updates_information_about_a_file_only_if_it_knows_the_latest_version($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer foo', 'If-Match' => 'my-version'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update(0, 'foo', ['bar' => 'baz'], 'my-version')->shouldReturn('response');
    }

    function it_puts_a_lock_on_files($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['lock' => ['type' => 'lock', 'expires_at' => 'bar', 'is_download_prevented' => 'baz']]
        ])->shouldBeCalled();

        $this->lock(0, 'foo', 'bar', 'baz');
    }

    function it_unlocks_files($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['lock' => null]
        ])->shouldBeCalled();

        $this->unlock(0, 'foo');

    }
}
