<?php

namespace Modules\User\Http\Requests;

use Urameshibr\Requests\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // "name" => "required",
        ];
    }
}
