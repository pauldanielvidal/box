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

}
