<?php

namespace Tests\Integration\User;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Modules\User\Services\Contracts\CreateUserServiceInterface;

class CreateUserServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_user()
    {
        $uuid = Str::uuid();

        $user = [
            'id' => $uuid,
            'fullname' => 'Neil Fraser',
            'email' => 'neil.fraser@universe.com',
            'password' => 'neil.password',
            'document' => '35842800870',
            'type' => 'customer'
        ];

        ExecuteService::execute(
            service:
            CreateUserServiceInterface::class,
            parameters:
            [
                "attributes" => $user,
            ],
        );

        $this->seeInDatabase("users", [
            'id' => $uuid,
            'fullname' => 'Neil Fraser',
            'email' => 'neil.fraser@universe.com',
            'password' => 'neil.password',
            'document' => '35842800870',
            'type' => 'customer'
        ]);
    }
}
