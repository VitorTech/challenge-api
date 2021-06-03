<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use App\Facades\ExecuteService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\GetAllUsersService;
use Modules\User\Services\CreateUserService;
use Modules\User\Services\UpdateUserService;
use Modules\User\Services\GetUserByIdService;
use Modules\User\Services\DeleteUserByIdService;


class UserController extends Controller
{
    public function index(): Collection | LengthAwarePaginator
    {
        return ExecuteService::execute(service: GetAllUsersService::class);
    }

    public function edit(string $id): ?User
    {
        return ExecuteService::execute(service: GetUserByIdService::class, parameters: [
            "id" => $id,
        ]);
    }

    public function store(CreateUserRequest $request): User
    {
        return ExecuteService::execute(service: CreateUserService::class, parameters: [
            "attributes" => $request->all(),
        ]);
    }

    public function update(string $id, UpdateUserRequest $request): bool
    {
        $user = ExecuteService::execute(service: GetUserByIdService::class, parameters: [
            "id" => $id,
        ]);

        return ExecuteService::execute(service: UpdateUserService::class, parameters: [
            "user" => $user,
            "attributes" => $request->all(),
        ]);
    }

    public function delete(string $id): bool
    {
        return ExecuteService::execute(service: DeleteUserByIdService::class, parameters: [
            "id" => $id,
        ]);
    }
}