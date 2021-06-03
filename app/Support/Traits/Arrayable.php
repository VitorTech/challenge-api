<?php

namespace App\Support\Traits;

trait Arrayable
{
    public function end(string $delimiter = ',', string $string)
    {
        $explode_string = explode($delimiter, $string);

        if (getType($explode_string) != 'array') {
            return $string;
        }

        return end($explode_string);
    }
}
