<?php

namespace Modules\Transaction\Repositories;

use App\Models\Transaction;
use App\Support\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

/**
 * Transaction repository class
 */

class TransactionRepository extends BaseRepository 
implements TransactionRepositoryInterface
{
    /**
     * @var [Model]
     */
    protected $model = Transaction::class;

    public function filter(
        ?string $filter,
        array $columns = ["*"],
        array $relationships = [],
        ?int $page = null,
        string $orderBy = "id"
    ): Collection | LengthAwarePaginator {
        $collection = $this->model->select($columns)->with($relationships);

        if ($filter) {
            $collection = $collection->where("name", "ilike", "%". $filter ."%");
        }

        if ($page) {
            request()->merge(["page" => $page]);

            return $collection->paginate();
        }

        return $collection->get();
    }
}
