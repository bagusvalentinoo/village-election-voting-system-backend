<?php

namespace App\Http\Resources\Voter;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Voter For General Resource
 *
 * @mixin \App\Models\User\Voter
 */
class VoterForGeneralResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nik' => $this->nik,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date,
            'address' => $this->address,
            'gender' => $this->gender,
            'otp' => $this->otp,
            'otp_status' => $this->otp_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'election_session' => $this->election_session
        ];
    }
}
