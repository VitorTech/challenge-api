<?php

namespace App\Models\Traits;

use DateTimeInterface;

trait UsesSerializeDates
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return match(app()->getLocale()) {
            'pt-BR', 'es' => $date->format('d/m/Y H:i:s'),
            'en' => $date->format('Y-m-d h:i:s'),
            default => $date->format('Y-m-d h:i:s')
        };
    }
}
