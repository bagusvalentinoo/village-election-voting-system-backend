<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'name' => [
                'required', 'string'
            ],
            'username' => [
                'required', 'string', 'unique:users,username'
            ],
            'password' => [
                'required', 'string', 'min:8'
            ],

            'role_ids' => [
                'required', 'array', 'min:1'
            ],
            'role_ids.*' => [
                'required', 'uuid', 'exists:roles,id'
            ]
        ];
    }
}
