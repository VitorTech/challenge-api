<?php

namespace Tests\Unit;

use TestCase;
use App\Models\Transaction;

class TransactionTest extends TestCase
{    
    /**
     * Fillable comparison test function.
     *
     * @return void
     */
    public function test_transaction_fillable()
    {
        $transaction = new Transaction();

        $expected = ['id', 'payer_id', 'payee_id', 'value'];

        $array_compared = array_diff($expected, $transaction->getFillable());

        $this->assertEquals(0, count($array_compared));
    }
}
        