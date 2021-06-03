<?php

namespace App\Support\Fakes;

use App\Support\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class FakeBaseRepository implements RepositoryInterface
{
    protected string $model;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $items;

    public function all(
        array $columns = ['*'],
        array $relationships = []
    ): Collection {
        return new Collection($this->items);
    }

    public function paginate(
        int $limit = 15,
        array $columns = ['*'],
        array $relationships = []
    ): LengthAwarePaginator {
        return new LengthAwarePaginator($this->items, 10, 15);
    }

    public function findById(
        string $id,
        array $columns = ['*'],
        array $relationships = []
    ): ?Model {
        return $this->items->find($id);
    }

    public function create(array $attributes): Model
    {
        $model = new ($this->model);

        if ($attributes && count($attributes)) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
            foreach ($attributes as $key => $attribute) {
                $model->$key = $attribute;
            }
        }

        $this->items = $this->items
            ? $this->items->push($model)
            : new Collection([$model]);

        return $model;
    }

    public function update(array $attributes, string $id): bool
    {
        return true;
    }
}
