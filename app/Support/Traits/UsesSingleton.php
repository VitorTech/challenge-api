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
        $self_class = self::class;

        self::$instance = self::$instance ?? new $self_class();

        return self::$instance;
    }
}
