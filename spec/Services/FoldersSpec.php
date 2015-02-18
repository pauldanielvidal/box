<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class FoldersSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Folders');
    }

    function it_fetches_the_items_of_folders($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/items', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => []
        ])->willReturn('response');

        $this->getItems('my-secret-token', 0)->shouldReturn('response');
    }

    function it_fetches_specific_fields_of_the_items_with_limit_and_offset($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/items', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => ['fields' => 'name,created_at', 'limit' => 100, 'offset' => 200]
        ])->willReturn('response');

        $this->getItems('my-secret-token', 0, ['name', 'created_at'], 100, 200)->shouldReturn('response');
    }

    function it_creates_new_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->create('my-secret-token', 'bar', 'baz')->shouldReturn('response');
    }

    function it_fetches_information_about_a_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->get('my-secret-token', 0)->shouldReturn('response');
    }

    function it_updates_information_about_a_folder($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update('my-secret-token', 0, ['bar' => 'baz'])->shouldReturn('response');
    }

    function it_updates_information_about_a_folder_only_if_it_knows_the_latest_version($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'If-Match' => 'my-version'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update('my-secret-token', 0, ['bar' => 'baz'], 'my-version')->shouldReturn('response');
    }

    function it_deletes_folders($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => []
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 0);
    }

    function it_deletes_folders_recursively_and_returns_a_subset_of_attributes($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => ['fields' => 'name,created_at', 'recursive' => true]
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 0, ['name', 'created_at'], true);
    }

    function it_deletes_folders_only_if_it_knows_the_latest_version($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token', 'If-Match' => 'my-version'],
            'query' => []
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 0, [], null, 'my-version');
    }

    function it_copies_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders/0/copy', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->copy('my-secret-token', 0, 'bar', 'baz')->shouldReturn('response');
    }

    function it_creates_shared_links($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
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
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['shared_link' => null]
        ])->willReturn('response');

        $this->deleteSharedLink('my-secret-token', 0)->shouldReturn('response');
    }

    function it_gets_the_collaborations_for_a_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/collaborations', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getCollaborations('my-secret-token', 0)->shouldReturn('response');
    }

    function it_gets_the_items_in_the_trash($http)
    {
        $http->get('https://api.box.com/2.0/folders/trash/items', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'query' => []
        ])->willReturn('response');

        $this->getTrash('my-secret-token')->shouldReturn('response');
    }

    function it_gets_a_trashed_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/trash', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getTrashed('my-secret-token', 0)->shouldReturn('response');
    }

    function it_deletes_trashed_folders($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0/trash', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->deleteTrashed('my-secret-token', 0);
    }

    function it_restores_trashed_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->restoreTrashed('my-secret-token', 0, 'bar', 'baz')->shouldReturn('response');
    }

}
