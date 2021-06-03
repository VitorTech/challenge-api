<?php

namespace App\Support\Contracts;

interface ServiceInterface
{
    public function setParameters(array $parameters = []): void;

    public function setRepository(RepositoryInterface $repository): void;

    public function execute(): mixed;
}
