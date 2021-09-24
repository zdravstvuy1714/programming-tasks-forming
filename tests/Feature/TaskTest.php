<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\CategoryRepository;
use App\Services\TaskService;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_update_current_tasks_immediately_after_registration()
    {
        (new DatabaseSeeder())->run();

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

        Sanctum::actingAs(
            User::first(),
            ['*'],
        );

        $this->json('GET', '/api/tasks')
            ->assertJson(function (AssertableJson $json) use ($registrationAttributes) {
                $json
                    ->has('tasks')
                    ->count('tasks', 7)
                    ->etc();
            });
    }

    public function test_user_can_view_mark_current_task_as_completed()
    {
        (new DatabaseSeeder())->run();

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

        Sanctum::actingAs(
            User::first(),
            ['*'],
        );

        $task = collect(DB::select('SELECT * FROM current_user_task'))->random();

        $this->json('POST', "/api/tasks/{$task->task_id}/complete")
            ->assertStatus(200);

        $this->assertDatabaseCount('completed_user_task', 1);
    }

    public function test_user_can_skip_current_task()
    {
        (new DatabaseSeeder())->run();

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

        Sanctum::actingAs(
            User::first(),
            ['*'],
        );

        $task = collect(DB::select('SELECT * FROM current_user_task'))->random();

        $this->json('POST', "/api/tasks/{$task->task_id}/skip")
            ->assertStatus(200);

        $this->assertDatabaseCount('skipped_user_task', 1);
    }
}
