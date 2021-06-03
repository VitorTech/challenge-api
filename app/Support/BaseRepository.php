<?php

namespace App\Support;

use App\Support\Contracts\RepositoryInterface;
use App\Support\Traits\Serializable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BaseRepository implements RepositoryInterface
{
    use Serializable;

    protected $model;

    protected Cache $cache;

    protected array $tags;

    protected ?string $authUserId;

    public function __construct(Cache $cache)
    {
        $this->model = $this->model();

        $this->cache = $cache;

        $this->tags = [$this->model->getTable()];

        $this->authUserId = auth()->user()->id ?? null;
    }

    protected function model(): Model
    {
        return app($this->model);
    }

    public function all(
        array $columns = ['*'],
        array $relationships = []
    ): Collection {
        $key = $this->serializeArgs(
            $this->model->getTable(),
            $columns,
            $relationships,
            $this->authUserId,
            'all'
        );

        if (!$this->cache::tags($this->tags)->get($key)) {
            $value = $this->model
                ->select($columns)
                ->with($relationships)
                ->get();

            $this->cache
                ::tags($this->tags)
                ->put($key, $value, env('CACHE_TIMER'));
        }

        return $this->cache::tags($this->tags)->get($key);
    }

    public function paginate(
        int $limit = 15,
        array $columns = ['*'],
        array $relationships = []
    ): LengthAwarePaginator {
        $page = request()->page ?? 1;

        $key = $this->serializeArgs(
            $this->model->getTable(),
            $limit,
            $columns,
            $relationships,
            $page,
            $this->authUserId,
            'paginate'
        );

        if (!$this->cache::tags($this->tags)->get($key)) {
            $value = $this->model
                ->select($columns)
                ->with($relationships)
                ->paginate($limit);

            $this->cache
                ::tags($this->tags)
                ->put($key, $value, env('CACHE_TIMER'));
        }

        return $this->cache::tags($this->tags)->get($key);
    }

    public function findById(
        string $id,
        array $columns = ['*'],
        array $relationships = []
    ): ?Model {
        $this->cache::tags($this->tags)->flush();

        $key = $this->serializeArgs(
            $this->model->getTable(),
            $id,
            $columns,
            $relationships,
            $this->authUserId,
            'findById'
        );

        if (!$this->cache::tags($this->tags)->get($key)) {
            $value = $this->model
                ->select($columns)
                ->with($relationships)
                ->find($id);

            $this->cache
                ::tags($this->tags)
                ->put($key, $value, env('CACHE_TIMER'));
        }

        return $this->cache::tags($this->tags)->get($key);
    }

    public function create(array $attributes): Model
    {
        $user = $this->model->create($attributes);

        $this->cache::tags($this->tags)->flush();

        return $user;
    }

    public function update(array $attributes, string $id): bool
    {
        $model = $this->findById($id);

        $user = $model->update($attributes);

        $this->cache::tags($this->tags)->flush();

        return $user;
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);

        $deleted = $model->delete();

        $this->cache::tags($this->tags)->flush();

        return $deleted;
    }
}
