<?php

namespace Modules\Transaction\Jobs;

use App\Jobs\Job;
use App\Models\User;
use Illuminate\Support\Facades\Http;

/**
 * Create Transaction Job
 * 
 * @author Vitor Ferreira <vitorg_s@hotmail.com>
 */
class CreatedTransactionJob extends Job
{    
    /**
     * Construct function.
     *
     * @param  mixed $payee
     * @return void
     */
    public function __construct() 
    {
        //
    }
    
    /**
     * Handle transaction job
     *
     * @return bool
     */
    public function handle(): bool
    {
        $response = Http::get('http://o4d9z.mocklab.io/notify');

        $response = $response->json();

        return $response['message'] == 'Success';
    }
}
