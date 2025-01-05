<?php

namespace App\Helpers\Model;

class VoterHelper
{
    public const RESOURCE_KEY_NAME = 'voter';

    public const OTP_STATUSES = [
        'not_used' => 'not_used',
        'used' => 'used',
    ];

    public const GENDERS = [
        'male' => 'male',
        'female' => 'female'
    ];
}
