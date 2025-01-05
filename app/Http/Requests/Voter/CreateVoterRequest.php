<?php

namespace App\Http\Requests\Voter;

use App\Helpers\Model\VoterHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateVoterRequest extends FormRequest
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
                'required',
                'uuid',
                'exists:election_sessions,id'
            ],
            'nik' => [
                'required',
                'string',
                'max:16',
                'unique:voters,nik'
            ],
            'full_name' => [
                'required',
                'string',
                'max:255'
            ],
            'birth_date' => [
                'required',
                'date',
                'date_format:Y-m-d'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],
            'gender' => [
                'required',
                'string',
                'in:' . implode(',', array_values(VoterHelper::GENDERS))
            ]
        ];
    }
}
