<?php

namespace Database\Seeders;

use App\Facades\ExecuteService;
use Illuminate\Database\Seeder;
use Modules\User\Services\CreateUserService;
use Modules\User\Services\GetUserByIdService;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 'f36443bb-15bf-41f4-8e52-71183a4065a6',
                'fullname' => 'Neil Fraser',
                'email' => 'neil.fraser@universe.com',
                'password' => 'neil.password',
                'document' => '35842800870',
                'type' => 'customer',
                'balance' => 5000.00
            ],
            [
                'id' => '4c8d5b2b-6ce1-4824-9f61-a65864f7bc5a',
                'fullname' => 'Carl Sagan',
                'email' => 'carl.sagan@universe.com',
                'password' => 'carl.password',
                'document' => '30802395000107',
                'type' => 'shopkeeper',
                'balance' => 15000.00
            ]
        ];

        foreach ($users as $user) {
            $has_user = ExecuteService::execute(
                service: GetUserByIdService::class,
                parameters: ['id' => $user['id']]
            );

            if (!$has_user) {
                ExecuteService::execute(
                    service: CreateUserService::class, 
                    parameters: ['attributes' => $user]
                );
            }
        }
    }
}
