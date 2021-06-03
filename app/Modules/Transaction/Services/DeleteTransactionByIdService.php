<?php

namespace Modules\Transaction\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class DeleteTransactionByIdService implements ServiceInterface
{
    private TransactionRepositoryInterface $repository;

    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->repository = app(TransactionRepositoryInterface::class);

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
