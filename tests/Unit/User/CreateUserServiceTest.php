<?php

namespace Tests\Unit\User;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Illuminate\Support\Facades\Hash;
use Modules\User\Repositories\Contracts\FakeUserRepositoryInterface;
use Modules\User\Services\Contracts\CreateUserServiceInterface;
use Modules\User\Services\GetUserByIdService;

class CreateUserServiceTest extends TestCase
{
    public function test_create_user()
    {
        $uuid = Str::uuid();
        
        $user_data = [
            'id' => $uuid,
            'fullname' => 'Neil Fraser',
            'email' => 'neil.fraser@universe.com',
            'password' => 'neil.password',
            'document' => '35842800870',
            'type' => 'customer',
        ];

        $user = ExecuteService::execute(
            service:
            CreateUserServiceInterface::class,
            parameters:
            [
                "attributes" => $user_data
            ],
            repository:
            FakeUserRepositoryInterface::class
        );

        $find_user = ExecuteService::execute(
            service: GetUserByIdService::class,
            parameters: ['id' => $user->id],
            repository: FakeUserRepositoryInterface::class
        );

        $this->assertArrayHasKey('id', $find_user->getAttributes());
        $this->assertArrayHasKey('fullname', $find_user->getAttributes());
        $this->assertArrayHasKey('email', $find_user->getAttributes());
        $this->assertArrayHasKey('document', $find_user->getAttributes());
        $this->assertArrayHasKey('password', $find_user->getAttributes());
        $this->assertArrayHasKey('type', $find_user->getAttributes());

        $this->assertEquals('id', $uuid);
        $this->assertEquals('fullname', $user_data['fullname']);
        $this->assertEquals('email', $user_data['email']);
        $this->assertEquals('document', $user_data['document']);
        $this->assertEquals('password', Hash::make('neil.password'));
        $this->assertEquals('type', $user_data['type']);

    }
}
