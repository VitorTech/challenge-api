<?php

namespace App\Providers;

use App\Support\ExecuteService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ExecuteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        App::bind('executeservice', function () {
            return new ExecuteService();
        });
    }
}
