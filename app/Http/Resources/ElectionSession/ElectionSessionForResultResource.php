<?php

namespace App\Http\Resources\ElectionSession;

use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\CandidatePair\CandidatePairForResultResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Election Session For Voting Resource
 *
 * @mixin \App\Models\Election\ElectionSession
 */
class ElectionSessionForResultResource extends JsonResource
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
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'candidate_pairs' => new BaseResourceCollection(
                $this->candidate_pairs, CandidatePairForResultResource::class
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
