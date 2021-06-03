<?php

namespace App\Support;

use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;

class ExecuteService
{
    public function execute(
        string $service,
        array $parameters = [],
        string $repository = '',
    ): mixed {
        $service = $this->getInstanceService($service);

        $service->setParameters($parameters);

        if ($repository) {
            $repository = $this->getInstanceRepository($repository);

            $service->setRepository($repository);
        }

        return $service->execute();
    }

    private function getInstanceService(string $service)
    {
        $service = app($service);

        $this->validateService($service);

        return $service;
    }

    private function validateService(ServiceInterface $service)
    {
        //
    }

    private function getInstanceRepository(string $repository)
    {
        $repository = app($repository);

        $this->validateRepository($repository);

        return $repository;
    }

    private function validateRepository(RepositoryInterface $repository)
    {
        //
    }
}
