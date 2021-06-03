<?php

namespace Tests\Unit;

use TestCase;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    public function test_transaction_fillable()
    {
        $transaction = new Transaction();

        $expected = ["id"];

        $array_compared = array_diff($expected, $transaction->getFillable());

        $this->assertEquals(0, count($array_compared));
    }
}
        