<?php

namespace App\Http\Requests\CandidatePair;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteCandidatePairRequest extends FormRequest
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
                'min:1'
            ],
            'ids.*' => [
                'uuid',
                'distinct',
                'exists:candidate_pairs,id'
            ]
        ];
    }
}
