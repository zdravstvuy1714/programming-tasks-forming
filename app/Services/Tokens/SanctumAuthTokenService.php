<?php

namespace App\Services\Tokens;

use App\Contracts\Auth\AuthTokenService;
use App\Models\User;
use Illuminate\Http\Request;

class SanctumAuthTokenService implements AuthTokenService
{
    public function create(User $user, Request $request): string
    {
        $token = $user->createToken($request->device_name)->plainTextToken;

        return $token;
    }

    public function destroy(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
