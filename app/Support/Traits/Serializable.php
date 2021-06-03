<?php

namespace App\Support\Traits;

trait Serializable
{
    public function serializeArgs(...$args)
    {
        return base64_encode(json_encode($args));
    }
}
