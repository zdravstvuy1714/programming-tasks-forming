<?php

namespace App\Contracts\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthTokenService
{
    public function create(User $user, Request $request): string;

    public function destroy(Request $request): void;
}
