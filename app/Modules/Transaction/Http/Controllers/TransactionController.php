<?php

namespace Modules\Transaction\Http\Controllers;

use App\Models\Transaction;
use App\Facades\ExecuteService;
use App\Http\Controllers\Controller;
use Modules\Transaction\Http\Requests\CreateTransactionRequest;
use Modules\Transaction\Services\CreateTransactionService;

class TransactionController extends Controller
{    
    /**
     * Store a transaction in storage.
     *
     * @param  mixed $request
     * @return Transaction
     */
    public function store(CreateTransactionRequest $request): Transaction
    {
        return ExecuteService::execute(
            service: CreateTransactionService::class, 
            parameters: ["attributes" => $request->all()]
        );
    }
}