<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Auth\AuthTokenService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreSessionRequest;
use App\Repositories\Contracts\UserRepository;
use App\Services\Auth\AuthSessionService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    private AuthTokenService $authTokenService;

    private UserRepository $userRepository;

    private AuthSessionService $authSessionService;

    public function __construct
    (
        AuthTokenService $authTokenService,
        UserRepository $userRepository,
        AuthSessionService $authSessionService
    )
    {
        $this->middleware('auth:sanctum')
            ->only('destroy');
        $this->authTokenService = $authTokenService;
        $this->userRepository = $userRepository;
        $this->authSessionService = $authSessionService;
    }

    public function store(StoreSessionRequest $request)
    {
        $user = $this->userRepository
            ->findWhere([
                'email' => $request->email,
            ])
            ->first();

        $token = $this->authSessionService
            ->attempt($user, $request);

        return response([
            'message'   => trans('auth.created_session'),
            'token'     => $token,
        ], 200);
    }

    public function destroy(Request $request)
    {
        $this->authTokenService
            ->destroy($request);

        return response([
            'message' => trans('auth.terminated_session'),
        ], 200);
    }
}
