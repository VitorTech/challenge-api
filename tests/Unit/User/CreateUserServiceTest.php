<?php

namespace Tests\Unit\User;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use App\Models\User;

use Modules\User\Repositories\UserRepository;
use Modules\User\Services\CreateUserService;
use Modules\User\Services\GetUserByIdService;

/**
 * Create user unit test suit
 * 
 * @author Vitor Ferreira <vitorg_s@hotmail.com>
 */
class CreateUserServiceTest extends TestCase
{       
    /**
     * Create user test function.
     *
     * @return void
     */
    public function test_create_user()
    {            
        $user_data = User::factory()->make()->toArray();

        $user_data['password'] = Str::random(8);

        $user = ExecuteService::execute(
            service:
            CreateUserService::class,
            parameters:
            [
                'attributes' => $user_data
            ],
            repository:
            UserRepository::class
        );

        $find_user = ExecuteService::execute(
            service: GetUserByIdService::class,
            parameters: ['id' => $user->id],
            repository: UserRepository::class
        );

        $this->assertArrayHasKey('id', $find_user->getAttributes());
        $this->assertArrayHasKey('fullname', $find_user->getAttributes());
        $this->assertArrayHasKey('email', $find_user->getAttributes());
        $this->assertArrayHasKey('document', $find_user->getAttributes());
        $this->assertArrayHasKey('password', $find_user->getAttributes());
        $this->assertArrayHasKey('type', $find_user->getAttributes());

        $this->assertEquals($user_data['fullname'], $find_user->fullname);
        $this->assertEquals($user_data['email'], $find_user->email);
        $this->assertEquals($user_data['document'], $find_user->document);

        $this->assertEquals($user_data['type'], $find_user->type);
        $this->assertEquals($user_data['balance'], $find_user->balance);
    }
}
