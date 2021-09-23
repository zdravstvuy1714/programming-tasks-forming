<?php

namespace App\Providers;

use App\Contracts\Auth\AuthTokenService;
use App\Repositories\CategoryRepositoryEloquent;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\TaskRepository;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\TaskRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
use App\Services\Tokens\SanctumAuthTokenService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthTokenService::class, function ($app) {
            return new SanctumAuthTokenService();
        });

        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepositoryEloquent($app);
        });

        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepositoryEloquent($app);
        });

        $this->app->bind(TaskRepository::class, function ($app) {
            return new TaskRepositoryEloquent($app);
        });
    }

    public function boot()
    {
        //
    }
}
