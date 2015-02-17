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
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->get('my-secret-token', 0)->shouldReturn('response');
    }

    function it_uploads_files($http)
    {
        $http->post('https://upload.box.com/api/2.0/files/content', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'body' => [
                'attributes' => json_encode(['name' => 'baz', 'parent' => ['id' => 0]]),
            ]
        ], 'bar')->willReturn('response');

        $this->upload('my-secret-token', 'bar', 'baz', 0)->shouldReturn('response');
    }

    function it_updates_information_about_a_file($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update('my-secret-token', 0, ['bar' => 'baz'])->shouldReturn('response');
    }

    function it_updates_information_about_a_file_only_if_it_knows_the_latest_version($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'If-Match' => 'my-version'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update('my-secret-token', 0, ['bar' => 'baz'], 'my-version')->shouldReturn('response');
    }

    function it_puts_a_lock_on_files($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['lock' => ['type' => 'lock', 'expires_at' => 'bar', 'is_download_prevented' => 'baz']]
        ])->shouldBeCalled();

        $this->lock('my-secret-token', 0, 'bar', 'baz');
    }

    function it_unlocks_files($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['lock' => null]
        ])->shouldBeCalled();

        $this->unlock('my-secret-token', 0);
    }

    function it_downloads_files($http)
    {
        $http->download('https://api.box.com/2.0/files/0/content', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ], 'bar')->shouldBeCalled();

        $this->download('my-secret-token', 0, 'bar');
    }

    function it_conducts_preflight_checks($http)
    {
        $http->options('https://api.box.com/2.0/files/content', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 0], 'size' => 100]
        ])->willReturn('response');

        $this->preflightCheck('my-secret-token', 'bar', 0, 100)->shouldReturn('response');
    }

    function it_deletes_files($http)
    {
        $http->delete('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'If-Match' => 'bar'],
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 0, 'bar');
    }

    function it_uploads_new_versions_of_files($http)
    {
        $http->post('https://upload.box.com/api/2.0/files/0/content', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'If-Match' => 'baz'],
        ], 'bar')->willReturn('response');

        $this->uploadVersion('my-secret-token', 0, 'bar', 'baz')->shouldReturn('response');
    }

    function it_views_the_existing_versions_of_a_file($http)
    {
        $http->get('https://api.box.com/2.0/files/0/versions', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->getVersions('my-secret-token', 0)->shouldReturn('response');
    }

    function it_promotes_old_versions_of_files($http)
    {
        $http->post('https://api.box.com/2.0/files/0/versions/current', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['type' => 'file_version', 'id' => 100]
        ], null)->willReturn('response');

        $this->promoteVersion('my-secret-token', 0, 100)->shouldReturn('response');
    }

    function it_deletes_specific_versions_of_files($http)
    {
        $http->delete('https://api.box.com/2.0/files/0/versions/100', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->shouldBeCalled();

        $this->deleteVersion('my-secret-token', 0, 100);
    }

    function it_copies_files($http)
    {
        $http->post('https://api.box.com/2.0/files/0/copy', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['parent' => ['id' => 'bar'], 'name' => 'baz']
        ], null)->willReturn('response');

        $this->copy('my-secret-token', 0, 'bar', 'baz')->shouldReturn('response');
    }

    function it_creates_shared_links_for_files($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => [
                'shared_link' => [
                    'access' => 'bar',
                    'unshared_at' => '2015-01-01',
                    'permissions' => [
                        'can_download' => true,
                        'can_preview' => true
                    ]
                ]
            ]
        ])->willReturn('response');

        $this->createSharedLink('my-secret-token', 0, 'bar', '2015-01-01', true, true)->shouldReturn('response');
    }

    function it_deletes_shared_links($http)
    {
        $http->put('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['shared_link' => null]
        ])->willReturn('response');

        $this->deleteSharedLink('my-secret-token', 0)->shouldReturn('response');
    }

    function it_gets_a_trashed_file($http)
    {
        $http->get('https://api.box.com/2.0/files/0/trash', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getTrashed('my-secret-token', 0)->shouldReturn('response');
    }

    function it_deletes_trashed_files($http)
    {
        $http->delete('https://api.box.com/2.0/files/0/trash', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->deleteTrashed('my-secret-token', 0);
    }

    function it_restores_trashed_files($http)
    {
        $http->post('https://api.box.com/2.0/files/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->restoreTrashed('my-secret-token', 0, 'bar', 'baz')->shouldReturn('response');
    }

    function it_gets_the_comments_on_a_file($http)
    {
        $http->get('https://api.box.com/2.0/files/0/comments', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => ['fields' => 'id,message']
        ])->willReturn('response');

        $this->getComments('my-secret-token', 0, ['id', 'message'])->shouldReturn('response');
    }

    function it_gets_the_tasks_for_a_file($http)
    {
        $http->get('https://api.box.com/2.0/files/152/tasks', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getTasks('my-secret-token', 152)->shouldReturn('response');
    }

    function it_gets_thumbnails_for_files($http)
    {
        $http->getRaw('https://api.box.com/2.0/files/152/thumbnail.png', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->thumbnail('my-secret-token', 152)->shouldReturn('response');
    }

}
