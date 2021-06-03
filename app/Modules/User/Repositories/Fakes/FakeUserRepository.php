<?php

namespace Modules\User\Repositories\Fakes;

use App\Models\User;
use App\Support\Fakes\FakeBaseRepository;
use App\Support\Traits\UsesSingleton;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Repositories\Contracts\FakeUserRepositoryInterface;

class FakeUserRepository extends FakeBaseRepository implements
    FakeUserRepositoryInterface
{
    use UsesSingleton;

    protected string $model = User::class;

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
