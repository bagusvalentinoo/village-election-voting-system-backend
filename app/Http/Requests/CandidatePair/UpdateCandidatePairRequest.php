<?php

namespace App\Http\Requests\CandidatePair;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatePairRequest extends FormRequest
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
            'election_session_id' => [
                'uuid',
                'exists:election_sessions,id'
            ],
            'first_candidate_name' => [
                'string'
            ],
            'second_candidate_name' => [
                'string'
            ],
            'description' => [
                'string'
            ],
            'image' => [
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048'
            ],
            'number' => [
                'integer',
                'unique:candidate_pairs,number,' . $this->route('param')
            ]
        ];
    }
}
