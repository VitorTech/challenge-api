<?php

namespace Modules\Transaction\Services;

use App\Models\Transaction;
use Error;
use App\Support\Contracts\RepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;
use Modules\Transaction\Services\Contracts\CreateTransactionServiceInterface;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Support\Statuses\Transaction as StatusesTransaction;
use Illuminate\Support\Facades\Queue;

use Illuminate\Support\Facades\DB;
use Modules\Transaction\Jobs\CreatedTransactionJob;
use Modules\User\Repositories\Contracts\UserRepositoryInterface;

/**
 * Transaction service class
 * 
 * @author Vitor Ferreira <vitorg_s@hotmail.com>
 */
class CreateTransactionService implements CreateTransactionServiceInterface
{
    private TransactionRepositoryInterface $repository;

    private array $parameters;
    
    /**
     * Construct service function.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = app(TransactionRepositoryInterface::class);
        $this->user_repository = app(UserRepositoryInterface::class);
    }
    
    /**
     * Set service repository
     *
     * @param mixed $_repository ServiceRepository
     * 
     * @return void
     */
    public function setRepository(RepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }
    
    /**
     * Set service parameters
     *
     * @param mixed $_parameters ServiceParameters
     * 
     * @return void
     */
    public function setParameters(array $parameters = []): void
    {
        $this->parameters = $parameters;
    }
    
    /**
     * Executes the service logic.
     *
     * @return mixed
     */
    public function execute(): mixed
    {
        DB::beginTransaction();

        $data = $this->parameters['attributes'];

        if (!isset($data['is_request'])) {
            $this->validateParameters();
        }

        $payer = $this->getUser($data['payer']);
        $payee = $this->getUser($data['payee']);

        $this->validatePayerType($payer);

        $this->checkCustomerBalance($payer, $data['value']);

        $transaction = $this->createTransaction($payer, $payee, $data['value']);

        if (isset($transaction['id'])) {
            $this->decrementPayerBalance($payer, $data['value']);   
            $this->incrementPayeeBalance($payee, $data['value']);   
        }

        DB::commit();

        $this->notifyPayee();

        return $transaction;
    }
    
    /**
     * Discounts a determined value from especific user balance.
     *
     * @param User  $payer $payer model instance
     * @param float $value Value to decrement
     * 
     * @return void
     */
    private function decrementPayerBalance(User $payer, float $value): void
    {
        $payer->decrement('balance', $value);
    }
    
    /**
     * Increment a determined value to specific user balance.
     *
     * @param User  $payee Payee model instance 
     * @param float $value Value to increment
     * 
     * @return void
     */
    private function incrementPayeeBalance(User $payee, float $value): void
    {
        $payee->increment('balance', $value);
    }
    
    /**
     * Checks if payer has sufficient balance to proceed. 
     * Otherwise, throws an error.
     *
     * @param User  $payer Payer model instance
     * @param float $value Transaction value
     * 
     * @throws Error If customer doesn't has enough balance.
     * @return void
     */
    private function checkCustomerBalance(User $payer, float $value) 
    {
        if ($payer->balance == 0 || $payer->balance < $value) {
            throw new Error('Insufficient customer balance', 400);
        }
    }
    
    /**
     * Check if payer is a customer. Otherwise, throws an error.
     *
     * @param User $payer Payer model instance
     * 
     * @throws Error If Payer is not a customer.
     * @return void
     */
    private function validatePayerType(User $payer): void
    {
        if ($payer->type == 'shopkeeper') {
            throw new Error('Payer must be a customer', 400);
        }
    }
    
    /**
     * Process and save the transaction.
     *
     * @param User  $payer Payer model instance
     * @param User  $payee Payee model instance
     * @param float $value Transaction value
     * 
     * @return Transaction
     */
    private function createTransaction(
        User $payer, User $payee, float $value
    ): Transaction {
        $status = $this->checkForTransferStatus();

        return $this->repository->create(
            [
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value' => $value,
                'status' => $status
            ]
        );
    }
    
    /**
     * Checks for transfer status in a external authorizer.
     *
     * @return string
     */
    private function checkForTransferStatus(): string
    {
        $response = Http::get(
            'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'
        ); 
        
        if ($response->failed()) {
            return StatusesTransaction::TRANSACTION_FAILED;
        }

        return $this->handleTransferStatusResponse($response->json());
    }
    
    /**
     * Handle the transfer status response
     *
     * @param mixed $response Status Response
     * 
     * @return string
     */
    private function handleTransferStatusResponse($response): string
    {
        if (!isset($response['message'])) {
            return 'TRANSACTION_FAILED';
        }

        return $response['message'] == 'Autorizado' ? 
            StatusesTransaction::TRANSACTION_SUCCEEDED: 
            StatusesTransaction::TRANSACTION_FAILED;
    }
    
    /**
     * Find and returns an user by ID.
     *
     * @param string $id User ID
     * 
     * @throws Error If Customer or Shopkeeper were not found.
     * @return User
     */
    private function getUser(string $id): ?User 
    {
        $user = $this->user_repository->findById($id);

        if (!$user) {
            throw new Error('Customer or Shopkeeper not found', 400);
        }

        return $user;
    }
    
    /**
     * Validate all the request parameters.
     * 
     * @throws Error If parameters validation fail.
     * @return void
     */
    private function validateParameters(): void
    {
        $attributes = $this->parameters["attributes"];

        $validator = Validator::make(
            $attributes, 
            [
                'payer' => 'required|string|exists:users,id',
                'payee' => 'required|string|exists:users,id',
                'value' => 'required|numeric|min:0'
            ]
        );

        if ($validator->fails()) {
            throw new Error(
                "The " . $validator->errors()->first() . " is incorrect", 422
            );
        }
    }
    
    /**
     * Notifies the payee with an SMS message about the transaction.
     *
     * @param User $payee Payee model instance
     * 
     * @return void
     */
    private function notifyPayee(): void
    {
        Queue::push(new CreatedTransactionJob());
    }
}
