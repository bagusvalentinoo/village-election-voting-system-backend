<?php

namespace App\Repositories\Election;

use App\Models\Election\ElectionSession;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface ElectionSessionRepository
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
     * @return \App\Models\Election\ElectionSession|null
     */
    public function getByParam(?string $param = null, array $payload = []): ElectionSession|null;

    /**
     * Create Election Session
     *
     * @param array $payload
     * @return \App\Models\Election\ElectionSession
     */
    public function create(array $payload): ElectionSession;

    /**
     * Update Election Session
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\Election\ElectionSession
     */
    public function updateByParam(string $param, array $payload): ElectionSession;

    /**
     * Bulk Delete Election Sessions
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null;
}
