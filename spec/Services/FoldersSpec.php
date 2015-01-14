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
            'headers' => ['Authorization' => 'Bearer foo'],
            'query' => []
        ])->willReturn('response');

        $this->getItems(0, 'foo')->shouldReturn('response');
    }

    function it_fetches_specific_fields_of_the_items_with_limit_and_offset($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/items', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'query' => ['fields' => 'name,created_at', 'limit' => 100, 'offset' => 200]
        ])->willReturn('response');

        $this->getItems(0, 'foo', ['name', 'created_at'], 100, 200)->shouldReturn('response');
    }

    function it_creates_new_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->create('foo', 'bar', 'baz')->shouldReturn('response');
    }

    function it_fetches_information_about_a_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo']
        ])->willReturn('response');

        $this->get(0, 'foo')->shouldReturn('response');
    }

    function it_updates_information_about_a_folder($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update(0, 'foo', ['bar' => 'baz'])->shouldReturn('response');
    }

    function it_updates_information_about_a_folder_only_if_it_knows_the_latest_version($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo', 'If-Match' => 'my-version'],
            'json' => ['bar' => 'baz']
        ])->willReturn('response');

        $this->update(0, 'foo', ['bar' => 'baz'], 'my-version')->shouldReturn('response');
    }

    function it_deletes_folders($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'query' => []
        ])->shouldBeCalled();

        $this->delete(0, 'foo');
    }

    function it_deletes_folders_recursively_and_returns_a_subset_of_attributes($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'query' => ['fields' => 'name,created_at', 'recursive' => true]
        ])->shouldBeCalled();

        $this->delete(0, 'foo', ['name', 'created_at'], true);
    }

    function it_deletes_folders_only_if_it_knows_the_latest_version($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo', 'If-Match' => 'my-version'],
            'query' => []
        ])->shouldBeCalled();

        $this->delete(0, 'foo', [], null, 'my-version');
    }

    function it_copies_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders/0/copy', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->copy(0, 'foo', 'bar', 'baz')->shouldReturn('response');
    }

    function it_creates_shared_links($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
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

        $this->createSharedLink(0, 'foo', 'bar', '2015-01-01', true, true)->shouldReturn('response');
    }

    function it_deletes_shared_links($http)
    {
        $http->put('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['shared_link' => null]
        ])->willReturn('response');

        $this->deleteSharedLink(0, 'foo')->shouldReturn('response');
    }

    function it_gets_the_collaborations_for_a_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/collaborations', [
            'headers' => ['Authorization' => 'Bearer foo']
        ])->willReturn('response');

        $this->getCollaborations(0, 'foo')->shouldReturn('response');
    }

    function it_gets_the_items_in_the_trash($http)
    {
        $http->get('https://api.box.com/2.0/folders/trash/items', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'query' => []
        ])->willReturn('response');

        $this->getTrash('foo')->shouldReturn('response');
    }

    function it_gets_a_trashed_folder($http)
    {
        $http->get('https://api.box.com/2.0/folders/0/trash', [
            'headers' => ['Authorization' => 'Bearer foo']
        ])->willReturn('response');

        $this->getTrashed(0, 'foo')->shouldReturn('response');
    }

    function it_deletes_trashed_folders($http)
    {
        $http->delete('https://api.box.com/2.0/folders/0/trash', [
            'headers' => ['Authorization' => 'Bearer foo']
        ])->shouldBeCalled();

        $this->deleteTrashed(0, 'foo');
    }

    function it_restores_trashed_folders($http)
    {
        $http->post('https://api.box.com/2.0/folders/0', [
            'headers' => ['Authorization' => 'Bearer foo'],
            'json' => ['name' => 'bar', 'parent' => ['id' => 'baz']]
        ], null)->willReturn('response');

        $this->restoreTrashed(0, 'foo', 'bar', 'baz')->shouldReturn('response');
    }

}
