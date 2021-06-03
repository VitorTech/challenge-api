<?php

namespace Modules\Transaction\Http\Requests;

use Urameshibr\Requests\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payer' => 'required|string|exists:users,id',
            'payee' => 'required|string|exists:users,id',
            'value' => 'required|numeric|min:0'
        ];
    }
}
