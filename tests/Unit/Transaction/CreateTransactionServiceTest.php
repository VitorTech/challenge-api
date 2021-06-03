<?php

namespace Tests\Unit\Transaction;

use TestCase;
use Illuminate\Support\Str;
use App\Facades\ExecuteService;
use Modules\Transaction\Repositories\Contracts\FakeTransactionRepositoryInterface;
use Modules\Transaction\Services\Contracts\CreateTransactionServiceInterface;
use Modules\Transaction\Services\GetTransactionByIdService;

class CreateTransactionServiceTest extends TestCase
{
    public function test_create_transaction()
    {
        $uuid = Str::uuid();

        $transaction = ExecuteService::execute(
            service:
            CreateTransactionServiceInterface::class,
            parameters:
            [
                'attributes' => ['id' => $uuid, 'payer' => 1, 'payee' => 2],
            ],
            repository:
            FakeTransactionRepositoryInterface::class
        );

        $find_transaction = ExecuteService::execute(
            service: GetTransactionByIdService::class,
            parameters: ["id" => $transaction->id],
            repository: FakeTransactionRepositoryInterface::class
        );

        $this->assertArrayHasKey("id", $find_transaction->getAttributes());
        $this->assertEquals("id", $uuid);
    }
}
