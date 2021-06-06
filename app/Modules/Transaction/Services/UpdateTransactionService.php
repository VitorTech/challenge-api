<?php

namespace Modules\Transaction\Services;

use Error;
use App\Models\Transaction;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Illuminate\Support\Facades\Validator;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class UpdateTransactionService implements ServiceInterface
{
    private TransactionRepositoryInterface $repository;

    private array $parameters;

    public function __construct()
    {
        $this->repository = app(TransactionRepositoryInterface::class);
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

        return $this->repository->update(
            $this->parameters["attributes"],
            $this->parameters["transaction"]->id
        );
    }

    private function validateParameters()
    {
        if (
            !isset($this->parameters["transaction"]) ||
            !$this->parameters["transaction"] instanceof Transaction
        ) {
            throw new Error(
                "Transaction is required and must to be an instance of Transaction"
            );
        }

        if (!isset($this->parameters["attributes"])) {
            throw new Error("The attributes are required");
        }

        $validator = Validator::make(
            $this->parameters['attributes'], 
            [
                'payer' => 'required|string|exists:users,id',
                'payee' => 'required|string|exists:users,id',
                'value' => 'required|numeric|min:0'
            ]
        );

        if ($validator->fails()) {
            throw new Error(
                $validator->errors()->first(), 422
            );
        }
    }
}
