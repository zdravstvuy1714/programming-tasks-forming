<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;

class UserService
{
    private UserRepository $repository;

    private Hasher $hash;

    public function __construct
    (
        UserRepository $repository,
        Hasher $hash
    )
    {
        $this->repository = $repository;
        $this->hash = $hash;
    }

    public function create(array $attributes): User
    {
        $user = $this->repository->create([
            'name'      => $attributes['name'],
            'email'     => $attributes['email'],
            'password'  => $this->hash->make($attributes['password']),
        ]);

        return $user;
    }
}
