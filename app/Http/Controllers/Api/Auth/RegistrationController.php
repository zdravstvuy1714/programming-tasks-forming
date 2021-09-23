<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Auth\AuthTokenService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    private UserService $userService;

    private AuthTokenService $authTokenService;

    private TaskService $taskService;

    public function __construct
    (
        UserService $userService,
        AuthTokenService $authTokenService,
        TaskService $taskService
    )
    {
        $this->userService = $userService;
        $this->authTokenService = $authTokenService;
        $this->taskService = $taskService;
    }

    public function store(RegistrationRequest $request)
    {
        $attributes = $request->validated();

        $user = $this->userService
            ->create($attributes);

        $token = $this->authTokenService
            ->create($user, $request);

        event(new Registered($user));

        return response([
            'message'   => trans('auth.registered'),
            'token'     => $token,
        ], 201);
    }
}
