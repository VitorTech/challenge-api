<?php

namespace Tests\Integration\User;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use App\Models\User;
use Faker\Factory;
use Modules\User\Services\CreateUserService;

/**
 * Create user integration test suit
 * 
 * @author Vitor Ferreira <vitorg_s@hotmail.com>
 */
class CreateUserServiceTest extends TestCase
{
    public function test_create_user()
    {
        $user = User::factory()->make()->toArray();
        $user['password'] = Str::random(8);

        ExecuteService::execute(
            service:
            CreateUserService::class,
            parameters:
            [
                'attributes' => $user,
            ],
        );

        $this->seeInDatabase(
            'users', 
            [
                'id' => $user['id'],
                'fullname' => $user['fullname'],
                'email' => $user['email'],
                'document' => $user['document'],
                'type' => $user['type'],
                'balance' => $user['balance']
            ]
        );
    }
}
