<?php

namespace App\Services\User;

use App\Models\User\Voter;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface VoterService
{
    /**
     * Get All Voters
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllVoters(Request $request): Collection|Paginator;

    /**
     * Get Voter By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\Voter|null
     */
    public function getVoterByParam(Request $request, string $param): Voter|null;

    /**
     * Create Voter
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\Voter
     */
    public function createVoter(Request $request): Voter;

    /**
     * Check Ongoing And Available Otp
     *
     * @param Request $request
     * @return void
     */
    public function checkOngoingAndAvailableOtp(Request $request): void;

    /**
     * Vote Candidate
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function voteCandidate(Request $request): void;

    /**
     * Update Voter
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateVoterByParam(Request $request, string $param): void;

    /**
     * Bulk Delete Voters
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteVoters(Request $request): void;
}
