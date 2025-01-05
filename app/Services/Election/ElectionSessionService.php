<?php

namespace App\Services\Election;

use App\Models\Election\ElectionSession;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ElectionSessionService
{
    /**
     * Get All Election Sessions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllElectionSessions(Request $request): Collection|Paginator;

    /**
     * Get Election Session By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\Election\ElectionSession|null
     */
    public function getElectionSessionByParam(Request $request, string $param): ElectionSession|null;

    /**
     * Create Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function createElectionSession(Request $request): void;

    /**
     * Update Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     */
    public function updateElectionSessionByParam(Request $request, string $param): void;

    /**
     * Bulk Delete Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function bulkDeleteElectionSessions(Request $request): void;
}
