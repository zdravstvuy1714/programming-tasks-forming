<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $loginAttributes = [
            'email'                 => $user->email,
            'password'              => 'password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/login', $loginAttributes)
            ->assertStatus(200)
            ->assertJson(function (AssertableJson $json) use ($loginAttributes) {
                $json
                    ->has('token')
                    ->etc();
            });
    }

    public function test_user_registration_require_email_attribute()
    {
        $registrationAttributes = [
            'email'                 => '',
            'password'              => 'test_password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/login', $registrationAttributes)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_registration_require_password_attribute()
    {
        $registrationAttributes = [
            'email'                 => 'john@mail.com',
            'password'              => '',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/login', $registrationAttributes)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_registration_require_device_name_attribute()
    {
        $registrationAttributes = [
            'email'                 => 'john@mail.com',
            'password'              => 'test_password',
            'device_name'           => '',
        ];

        $this->json('POST', '/api/login', $registrationAttributes)
            ->assertJsonValidationErrors(['device_name']);
    }

    public function test_user_can_logout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'],
        );

        $this->json('POST', '/api/logout')
            ->assertStatus(200);
    }
}
