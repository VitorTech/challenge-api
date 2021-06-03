<?php

namespace Modules\User\Services;

use App\Support\Contracts\RepositoryInterface;
use App\Support\Contracts\ServiceInterface;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class GetAllUsersService implements ServiceInterface
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
        $request = request();

        $filter = $this->parameters["filter"] ?? $request->filter;

        $columns = $this->setColumns();

        $relationships =
            $this->parameters["relationships"] ??
            ($request->relationships ?? []);

        $page = $this->parameters["page"] ?? $request->page;

        return $this->repository->filter(
            $filter,
            $columns,
            $relationships,
            $page
        );
    }

    private function setColumns(): array
    {
        $request_columns = request()->columns
            ? explode(",", request()->columns)
            : null;

        return $this->parameters["columns"] ?? ($request_columns ?? ["*"]);
    }
}
