<?php

namespace App\Repositories\Election;

use App\Models\Election\ElectionSession;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

readonly class ElectionSessionRepositoryImpl implements ElectionSessionRepository
{
    public function __construct(private ElectionSession $electionSession)
    {
        //
    }

    /**
     * Get Election Sessions
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAll(array $payload = []): Collection|Paginator
    {
        $electionSessions = $this->electionSession->baseFilter($payload);
        $electionSessions = $electionSessions
            ->when(isset($payload['for_ongoing_voting']), function ($query) {
                return $query->forOngoingVoting();
            })
            ->when(isset($payload['for_ongoing_result']), function ($query) {
                return $query->forOngoingResult();
            });

        if (isset($payload['paginate']) && $payload['paginate'])
            return $electionSessions->simplePaginate($payload['per_page'] ?? null);

        return $electionSessions->get();
    }

    /**
     * Get Election Session By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\Election\ElectionSession|null
     */
    public function getByParam(?string $param = null, array $payload = []): ElectionSession|null
    {
        $electionSession = $this->electionSession->when($param, function ($query) use ($param) {
            return $query->where('id', $param);
        });

        if ($payload['fail'] ?? false) return $electionSession->firstOrFail();
        return $electionSession->first();
    }

    /**
     * Create Election Session
     *
     * @param array $payload
     * @return \App\Models\Election\ElectionSession
     */
    public function create(array $payload): ElectionSession
    {
        return $this->electionSession->create(
            array_filter([
                'name' => $payload['name'] ?? null,
                'start_date' => $payload['start_date'] ?? null,
                'end_date' => $payload['end_date'] ?? null,
            ], isNotNullArrayFilter())
        );
    }

    /**
     * Update Election Session
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\Election\ElectionSession
     */
    public function updateByParam(string $param, array $payload): ElectionSession
    {
        $electionSession = $this->getByParam($param, ['fail' => true]);

        $electionSession->update(
            array_filter([
                'name' => $payload['name'] ?? null,
                'start_date' => $payload['start_date'] ?? null,
                'end_date' => $payload['end_date'] ?? null,
            ], isNotNullArrayFilter())
        );

        return $electionSession->refresh();
    }

    /**
     * Bulk Delete Election Sessions
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null
    {
        return $this->electionSession->whereIn('id', $payload['ids'])->delete();
    }
}
