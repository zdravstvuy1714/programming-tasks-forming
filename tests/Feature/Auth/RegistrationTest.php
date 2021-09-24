<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_register()
    {
        $registrationAttributes = [
            'name'                  => 'John',
            'email'                 => 'john@mail.com',
            'password'              => 'test_password',
            'password_confirmation' => 'test_password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertStatus(201)
            ->assertJson(function (AssertableJson $json) use ($registrationAttributes) {
                $json
                    ->has('token')
                    ->etc();
            });

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_registration_require_name_attribute()
    {
        $registrationAttributes = [
            'name'                  => '',
            'email'                 => 'john@mail.com',
            'password'              => 'test_password',
            'password_confirmation' => 'test_password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_registration_require_email_attribute()
    {
        $registrationAttributes = [
            'name'                  => 'john',
            'email'                 => '',
            'password'              => 'test_password',
            'password_confirmation' => 'test_password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_registration_require_password_attribute()
    {
        $registrationAttributes = [
            'name'                  => 'john',
            'email'                 => 'john@mail.com',
            'password'              => '',
            'password_confirmation' => 'test_password',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_registration_require_password_confirmation_attribute()
    {
        $registrationAttributes = [
            'name'                  => 'john',
            'email'                 => 'john@mail.com',
            'password'              => 'test_password',
            'password_confirmation' => '',
            'device_name'           => 'test device',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_user_registration_require_device_name_attribute()
    {
        $registrationAttributes = [
            'name'                  => 'john',
            'email'                 => 'john@mail.com',
            'password'              => 'test_password',
            'password_confirmation' => 'test_password',
            'device_name'           => '',
        ];

        $this->json('POST', '/api/registration', $registrationAttributes)
            ->assertJsonValidationErrors(['device_name']);
    }
}
