<?php

namespace App\Rules\User;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcludeUserLoggedInRule implements ValidationRule
{
    private string|null $currentUserId;

    public function __construct(string|null $currentUserId)
    {
        $this->currentUserId = $currentUserId;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array($this->currentUserId, $value)) $fail(trans('user.delete_exclude_user_logged_in'));
    }
}
