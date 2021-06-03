<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\User\Repositories\UserRepository;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = "Modules\User\Http\Controllers";

    public function register(): void
    {
        $this->loadApiRoutes();
        $this->bindInterfaces();
        $this->registerViews();
    }

    private function loadApiRoutes(): void
    {
        $this->app->router->group(
            [
                "namespace" => $this->moduleNamespace,
            ],
            function ($router) {
                require __DIR__ . "/../Routes/api.php";
            }
        );
    }

    private function bindInterfaces(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . "/../Resources/views", "user");
    }
}
