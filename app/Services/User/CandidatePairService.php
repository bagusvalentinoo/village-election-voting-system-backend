<?php

namespace App\Services\User;

use App\Models\User\CandidatePair;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface CandidatePairService
{
    /**
     * Get All Candidate Pairs
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllCandidatePairs(Request $request): Collection|Paginator;

    /**
     * Get Candidate Pair By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\CandidatePair|null
     */
    public function getCandidatePairByParam(Request $request, string $param): CandidatePair|null;

    /**
     * Get Candidate Pair For Vote Candidate
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\CandidatePair|null
     */
    public function getCandidatePairForVoteCandidate(Request $request, string $param): CandidatePair|null;

    /**
     * Create Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function createCandidatePair(Request $request): void;

    /**
     * Update Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     */
    public function updateCandidatePairByParam(Request $request, string $param): void;

    /**
     * Increment Total Vote For Vote Candidate
     *
     * @param Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function incrementTotalVoteForVoteCandidate(Request $request, string $param): void;

    /**
     * Bulk Delete Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function bulkDeleteCandidatePairs(Request $request): void;
}
