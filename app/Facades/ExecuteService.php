<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed execute(
 * string $service, array $parameters = [], $string $repository
 * )
 * The service executor
 */
class ExecuteService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'executeservice';
    }
}
