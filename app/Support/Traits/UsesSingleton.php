<?php

namespace App\Support\Traits;

trait UsesSingleton
{
    protected static $instance;

    private function __construct()
    {
        //
    }

    public static function getInstance(): object
    {
        $selfClass = self::class;

        self::$instance = self::$instance ?? new $selfClass();

        return self::$instance;
    }
}
