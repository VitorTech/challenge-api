<?php

namespace Modules\Transaction\Repositories\Contracts;

use App\Support\Contracts\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryInterface extends RepositoryInterface
{
    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
        string $orderBy = "id"
    ): Collection | LengthAwarePaginator;
}
