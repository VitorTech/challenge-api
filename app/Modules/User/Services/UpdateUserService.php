<?php

namespace Modules\User\Services;

use Error;
use App\Models\User;
use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Illuminate\Support\Facades\Validator;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserService implements ServiceInterface
{
    private UserRepositoryInterface $repository;

    private array $parameters;

    public function __construct()
    {
        $this->repository = app(UserRepositoryInterface::class);
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
            $this->parameters["user"]->id
        );
    }

    private function validateParameters()
    {
        if (
            !isset($this->parameters["user"]) ||
            !$this->parameters["user"] instanceof User
        ) {
            throw new Error(
                "The user is required and must to be instance of User"
            );
        }

        if (!isset($this->parameters["attributes"])) {
            throw new Error("The attributes are required");
        }

        $validator = Validator::make($this->parameters["attributes"], [
            "name" => "required",
        ]);

        if ($validator->fails()) {
            throw new Error(
                "The " . $validator->errors()->first() . " is incorrect"
            );
        }
    }
}
