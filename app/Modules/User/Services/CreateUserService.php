<?php

namespace Modules\User\Services;

use Error;
use App\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\Contracts\CreateUserServiceInterface;

class CreateUserService implements CreateUserServiceInterface
{
    private UserRepositoryInterface $repository;

    private array $parameters;

    public function __construct()
    {
        $this->repository = app(UserRepository::class);
    }
    
    /**
     * Defines the repository instance
     *
     * @param  mixed $repository
     * @return void
     */
    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }
    
    /**
     * Define service parameters
     *
     * @param  mixed $_parameters
     * @return void
     */
    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }
    
    /**
     * Create user logic
     *
     * @return mixed
     */
    public function execute(): mixed
    {
        $this->validateParameters();

        $password = $this->parameters['attributes']['password'] ?? Str::random(8);

        $attributes = $this->setAttibutes($password);

        return $this->repository->create($attributes);
    }

    private function validateParameters(): void
    {
        $attributes = $this->parameters['attributes'];

        $validator = Validator::make(
            $attributes, 
            [
                'fullname' => 'required',
                'email' => 'required|unique:users',
                'document' => 'required|unique:users',
                'password' => 'required',
                'type' => 'required'
            ]
        );

        if ($validator->fails()) {
            throw new Error(
                'The ' . $validator->errors()->first() . ' is incorrect'
            );
        }
    }
    
    /**
     * Handle service attributes
     *
     * @param  mixed $password
     * @return array
     */
    private function setAttibutes(string $password): array
    {
        $attributes = $this->parameters['attributes'];

        return [
            'id' => $attributes['id'] ?? null,
            'fullname' => $attributes['fullname'],
            'email' => $attributes['email'],
            'password' => app('hash')->make($password),
            'document' => $attributes['document'],
            'type' => $attributes['type'],
            'balance' => $attributes['balance'] ?? 0.00
        ];
    }

    
}
