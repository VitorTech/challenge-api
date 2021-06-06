<?php

namespace Modules\Transaction\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class GetTransactionByIdService implements ServiceInterface
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

        $columns = $this->setColumns();

        $relationships = $this->parameters['relationships'] ?? [];

        return $this->repository->findById(
            $this->parameters['id'],
            $columns,
            $relationships
        );
    }

    private function validateParameters()
    {
        if (!isset($this->parameters['id'])) {
            throw new Error('The id is required');
        }
    }

    private function setColumns(): array
    {
        $request_columns = request()->columns
            ? explode(",", request()->columns)
            : null;

        return $this->parameters['columns'] ?? ($request_columns ?? ['*']);
    }
}
