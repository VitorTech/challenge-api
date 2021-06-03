<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class RouteListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = Route::getRoutes();

        if (!$routes || !count($routes)) {
            echo 'No routes available';
            return;
        }

        foreach ($routes as $route) {
            $uses = $route['action']['uses'] ?? '';

            echo '
' .
                $route['method'] .
                ' ' .
                $route['uri'] .
                ' ' .
                $uses .
                '
';
        }
    }
}
