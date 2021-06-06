<?php

namespace Modules\User\Repositories;

use App\Models\User;
use App\Support\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    use HasFactory;
    
    /**
     * @var [Model]
     */
    protected $model = User::class;

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
