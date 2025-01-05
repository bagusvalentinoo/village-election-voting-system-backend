<?php

namespace App\Http\Requests\ElectionSession;

use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionSessionRequest extends FormRequest
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
                'string'
            ],
            'start_date' => [
                'date_format:' . config('app.timestamp_format')
            ],
            'end_date' => [
                'date_format:' . config('app.timestamp_format'), 'after:start_date'
            ]
        ];
    }
}
