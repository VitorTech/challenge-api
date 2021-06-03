<?php

namespace Tests\Integration\Transaction;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Modules\Transaction\Services\Contracts\CreateTransactionServiceInterface;

class CreateTransactionServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_transaction()
    {
        $uuid = Str::uuid();

        ExecuteService::execute(
            service:
            CreateTransactionServiceInterface::class,
            parameters:
            [
                "attributes" => ["id" => $uuid],
            ],
        );

        $this->seeInDatabase("products", [
            "id" => $uuid,
        ]);
    }
}
