<?php

namespace App\Rules\Voter;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueNik implements ValidationRule
{
    private string|null $electionSessionId;

    public function __construct(string|null $electionSessionId)
    {
        $this->electionSessionId = $electionSessionId;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = DB::table('voters')->where('nik', $value)
            ->when($this->electionSessionId, function ($query) {
                return $query->where('election_session_id', $this->electionSessionId);
            }, function ($query) {
                return $query->whereRaw('election_session_id = voters.election_session_id');
            });

        if ($query->exists()) $fail(trans('voter.unique_nik_each_election_session_id_error', [], 'id'));
    }
}
