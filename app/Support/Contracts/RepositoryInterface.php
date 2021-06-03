<?php

namespace App\Support\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(
        array $columns = ['*'],
        array $relationships = []
    ): Collection;

    public function paginate(
        int $limit = 15,
        array $columns = ['*'],
        array $relationships = []
    ): LengthAwarePaginator;

    public function findById(
        string $id,
        array $columns = ['*'],
        array $relationships = []
    ): ?Model;

    public function create(array $attributes): Model;

    public function update(array $attributes, string $id): bool;

    public function delete(string $id): bool;
}
