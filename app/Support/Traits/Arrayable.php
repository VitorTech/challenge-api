<?php

namespace App\Support\Traits;

trait Arrayable
{
    public function end(string $delimiter = ',', string $string)
    {
        $explodeString = explode($delimiter, $string);

        if (getType($explodeString) != 'array') {
            return $string;
        }

        return end($explodeString);
    }
}
