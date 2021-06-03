<?php

namespace Database\Seeders;

use App\Models\OauthClients;
use Illuminate\Database\Seeder;

class PassportClientsTableSeeder extends Seeder
{
    public function run(): void
    {
        $oauth_clients = [
            [
                'id' => '1',
                'name' => 'Challenge_API Personal Access Client',
                'secret' => 'ulCmLW6kIVCHWiMd7h2ZTKxTa7XIlC8lXfHxETFk',
                'provider' => null,
                'redirect' => env('APP_URL'),
                'personal_access_client' => true,
                'password_client' => false,
                'revoked' => false,
            ],
            [
                'id' => '2',
                'name' => 'Challenge_API Password Grant Client',
                'secret' => 'Zn7TuQMhGgQ4eseBtcNrqGjY4eAa9ItUeHPyFDpS',
                'provider' => 'users',
                'redirect' => env('APP_URL'),
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
            ],
        ];

        foreach ($oauth_clients as $oauth_client) {
            $has_client = OauthClients::find($oauth_client['id']);

            if (!$has_client) {
                OauthClients::create($oauth_client);
            }
        }
    }
}
