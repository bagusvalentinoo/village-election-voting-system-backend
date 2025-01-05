<?php

namespace App\Http\Requests\Voter;

use App\Helpers\Model\VoterHelper;
use App\Rules\Voter\UniqueNik;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVoterRequest extends FormRequest
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
            'nik' => [
                'string',
                'max:16',
                new UniqueNik($this->input('election_session_id'))
            ],
            'full_name' => [
                'string',
                'max:255'
            ],
            'birth_date' => [
                'date',
                'date_format:Y-m-d'
            ],
            'address' => [
                'string',
                'max:255'
            ],
            'gender' => [
                'string',
                'in:' . implode(',', array_values(VoterHelper::GENDERS))
            ]
        ];
    }
}
