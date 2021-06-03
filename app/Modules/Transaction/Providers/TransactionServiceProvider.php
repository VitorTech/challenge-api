<?php

namespace Modules\Transaction\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = "Modules\Transaction\Http\Controllers";

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
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
    }

    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . "/../Resources/views", "transaction");
    }
}
