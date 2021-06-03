<?php

namespace Modules\User\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class DeleteUserByIdService implements ServiceInterface
{
    private UserRepositoryInterface $repository;

    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->repository = app(UserRepositoryInterface::class);

        $this->parameters = $parameters;
    }

    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }

    public function execute(): mixed
    {
        $this->validateParameters();

        return $this->repository->delete($this->parameters["id"]);
    }

    private function validateParameters()
    {
        if (!isset($this->parameters["id"])) {
            throw new Error("The id is required");
        }
    }
}
