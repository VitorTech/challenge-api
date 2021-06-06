<?php

namespace Tests\Unit\Transaction;

use TestCase;
use App\Facades\ExecuteService;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\Transaction\Services\CreateTransactionService;
use Modules\Transaction\Services\GetTransactionByIdService;

class CreateTransactionServiceTest extends TestCase
{    
    /**
     * Create transaction service test suit
     *
     * @return void
     */
    public function test_create_transaction()
    {
        $payer_id = 'f36443bb-15bf-41f4-8e52-71183a4065a6';
        $payee_id = '4c8d5b2b-6ce1-4824-9f61-a65864f7bc5a';

        $transaction = ExecuteService::execute(
            service: CreateTransactionService::class,
            parameters:
            [
                'attributes' => [
                    'payer' => $payer_id, 
                    'payee' => $payee_id,
                    'value' => 2000
                ],
            ],
            repository: TransactionRepository::class
        );

        $find_transaction = ExecuteService::execute(
            service: GetTransactionByIdService::class,
            parameters: ["id" => $transaction->id],
            repository: TransactionRepository::class
        );

        $this->assertArrayHasKey("id", $find_transaction->getAttributes());
        $this->assertEquals($payer_id, $find_transaction->payer_id);
        $this->assertEquals($payee_id, $find_transaction->payee_id);

    }
}
