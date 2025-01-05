<?php

namespace App\Http\Requests\User;

use App\Rules\User\ExcludeUserLoggedInRule;
use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ids' => [
                'required',
                'array',
                'min:1',
                new ExcludeUserLoggedInRule($this->user()->id)
            ],
            'ids.*' => [
                'uuid',
                'distinct',
                'exists:users,id'
            ]
        ];
    }
}
