<?php

namespace spec\Romby\Box\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romby\Box\Http\HttpInterface;

class UsersSpec extends ObjectBehavior
{
    function let(HttpInterface $http)
    {
        $this->beConstructedWith($http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Romby\Box\Services\Users');
    }

    function it_fetches_information_about_the_current_user($http)
    {
        $http->get('https://api.box.com/2.0/users/me', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->me('my-secret-token')->shouldReturn('response');
    }

    function it_creates_enterprise_users($http)
    {
        $http->post('https://api.box.com/2.0/users', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => [
                'login' => 'johndoe@example.com',
                'name' => 'John Doe',
                'role' => 'user',
                'language' => 'da',
                'is_sync_enabled' => false,
                'job_title' => 'Consultant',
                'phone' => '12345678',
                'address' => 'Example Road 123',
                'space_amount' => 15.5,
                'tracking_codes' => [
                    'tracking_code_1' => 'abc123'
                ],
                'can_see_managed_users' => true,
                'status' => 'active',
                'timezone' => 'Europe/Copenhagen',
                'is_exempt_from_device_limits' => false,
                'is_exempt_from_login_verification' => true
            ]
        ], null)->willReturn('response');

        $this->create(
            'my-secret-token',
            'johndoe@example.com',
            'John Doe',
            [
                'role' => 'user',
                'language' => 'da',
                'is_sync_enabled' => false,
                'job_title' => 'Consultant',
                'phone' => '12345678',
                'address' => 'Example Road 123',
                'space_amount' => 15.5,
                'tracking_codes' => [
                    'tracking_code_1' => 'abc123'
                ],
                'can_see_managed_users' => true,
                'status' => 'active',
                'timezone' => 'Europe/Copenhagen',
                'is_exempt_from_device_limits' => false,
                'is_exempt_from_login_verification' => true
            ]
        )->shouldReturn('response');
    }

    function it_gets_all_users_in_an_enterprise($http)
    {
        $http->get('https://api.box.com/2.0/users', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->all('my-secret-token')->shouldReturn('response');
    }

    function it_gets_information_about_a_user($http)
    {
        $http->get('https://api.box.com/2.0/users/2314', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
        ])->willReturn('response');

        $this->get('my-secret-token', 2314)->shouldReturn('response');
    }

    function it_updates_information_about_a_user($http)
    {
        $http->put('https://api.box.com/2.0/users/2314', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['name' => 'New Username']
        ])->willReturn('response');

        $this->update('my-secret-token', 2314, ['name' => 'New Username'])->shouldReturn('response');
    }

    function it_deletes_enterprise_users($http)
    {
        $http->delete('https://api.box.com/2.0/users/2314', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->shouldBeCalled();

        $this->delete('my-secret-token', 2314);
    }

    function it_get_all_email_aliases_for_a_user($http)
    {
        $http->get('https://api.box.com/2.0/users/2314/email_aliases', [
            'headers' => ['Authorization' => 'Bearer my-secret-token']
        ])->willReturn('response');

        $this->getAllEmailAliases('my-secret-token', 2314)->shouldReturn('response');
    }

    function it_adds_email_aliases_for_a_user($http)
    {
        $http->post('https://api.box.com/2.0/users/2314/email_aliases', [
            'headers' => ['Authorization' => 'Bearer my-secret-token'],
            'json' => ['email' => 'aliasemail@example.com']
        ], null)->willReturn('response');

        $this->createEmailAlias('my-secret-token', 2314, 'aliasemail@example.com')->shouldReturn('response');
    }

}
