<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthTokenService;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthSessionService
{
    private Hasher $hash;

    private AuthTokenService $authTokenService;

    public function __construct
    (
        Hasher $hash,
        AuthTokenService $authTokenService
    )
    {
        $this->hash = $hash;
        $this->authTokenService = $authTokenService;
    }

    public function attempt(?User $user, Request $request): ValidationException|string
    {
        if (! $user || ! $this->hash->check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $token = $this->authTokenService
            ->create($user, $request);

        return $token;
    }
}
