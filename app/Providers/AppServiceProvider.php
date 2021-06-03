<?php

namespace App\Providers;

use Dusterio\LumenPassport\LumenPassport;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        LumenPassport::routes($this->app, ['middleware' => ['json.response']]);

        LumenPassport::tokensExpireIn(Carbon::now()->addDay());

        LumenPassport::allowMultipleTokens();
    }
}
