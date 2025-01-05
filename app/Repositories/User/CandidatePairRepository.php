<?php

namespace App\Repositories\User;

use App\Models\User\CandidatePair;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface CandidatePairRepository
{
    /**
     * Get Election Sessions
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAll(array $payload = []): Collection|Paginator;

    /**
     * Get Election Session By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\User\CandidatePair|null
     */
    public function getByParam(?string $param = null, array $payload = []): CandidatePair|null;

    /**
     * Create Election Session
     *
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function create(array $payload): CandidatePair;

    /**
     * Update Election Session
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function updateByParam(string $param, array $payload): CandidatePair;

    /**
     * Update Candidate Pair By Model
     *
     * @param \App\Models\User\CandidatePair $candidatePair
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function updateByModel(CandidatePair $candidatePair, array $payload): CandidatePair;

    /**
     * Increment Total Vote By Model
     *
     * @param \App\Models\User\CandidatePair $candidatePair
     * @param int $amount
     * @return \App\Models\User\CandidatePair
     */
    public function incrementTotalVoteByModel(CandidatePair $candidatePair, int $amount = 1): CandidatePair;

    /**
     * Bulk Delete Election Sessions
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null;
}
