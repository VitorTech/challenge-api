<?php

namespace Tests\Integration\User;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use App\Models\User;
use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Modules\User\Services\CreateUserService;

class CreateUserServiceTest extends TestCase
{

    public function test_create_user()
    {
        $faker = Factory::create();

        $user = User::factory()->make()->toArray();

        $user['email'] = $faker->unique()->safeEmail;
        $user['document'] = $faker->numerify('###########');
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
