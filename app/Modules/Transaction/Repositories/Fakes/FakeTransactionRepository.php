<?php

namespace Modules\Transaction\Repositories\Fakes;

use App\Models\Transaction;
use App\Support\Fakes\FakeBaseRepository;
use App\Support\Traits\UsesSingleton;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Transaction\Repositories\Contracts\FakeTransactionRepositoryInterface;

class FakeTransactionRepository extends FakeBaseRepository implements
    FakeTransactionRepositoryInterface
{
    use UsesSingleton;

    protected string $model = Transaction::class;

    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
    ): Collection | LengthAwarePaginator
    {
        return new Collection([]);
    }
}
