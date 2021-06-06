<?php

namespace Tests\Integration\Transaction;

use TestCase;
use App\Facades\ExecuteService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Modules\Transaction\Services\CreateTransactionService;

class CreateTransactionServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_transaction()
    {
        $payer_id = 'f36443bb-15bf-41f4-8e52-71183a4065a6';
        $payee_id = '4c8d5b2b-6ce1-4824-9f61-a65864f7bc5a';

        ExecuteService::execute(
            service:
            CreateTransactionService::class,
            parameters:
            [
                'attributes' => [
                    'payer' => $payer_id, 
                    'payee' => $payee_id, 
                    'value' => 2000
                ],
            ],
        );

        $this->seeInDatabase(
            'transactions', 
            [
                'payer_id' => $payer_id,
                'payee_id' => $payee_id,
                'value' => 2000
            ]
        );
    }
}
