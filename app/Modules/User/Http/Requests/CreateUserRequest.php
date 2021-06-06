<?php

namespace Modules\User\Http\Requests;

use Urameshibr\Requests\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'document' => 'required|unique:users',
            'password' => 'required',
            'type' => 'required'
        ];
    }
}
