<?php

namespace App\Repositories\User;

use App\Models\User\CandidatePair;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

readonly class CandidatePairRepositoryImpl implements CandidatePairRepository
{
    public function __construct(private CandidatePair $candidatePair)
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
        $candidatePairs = $this->candidatePair
            ->orderBy('number')
            ->baseFilter($payload);

        $candidatePairs = $candidatePairs
            ->when($payload['election_session_id'] ?? null, function ($query) use ($payload) {
                return $query->whereIn('election_session_id', explode(',', $payload['election_session_id']));
            });

        if (isset($payload['paginate']) && $payload['paginate'])
            return $candidatePairs->simplePaginate($payload['per_page'] ?? null);

        return $candidatePairs->get();
    }

    /**
     * Get Election Session By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\User\CandidatePair|null
     */
    public function getByParam(?string $param = null, array $payload = []): CandidatePair|null
    {
        $candidatePair = $this->candidatePair->baseFilter($payload);

        $candidatePair = $candidatePair
            ->when($param, function ($query) use ($param) {
                return $query->where('id', $param);
            })
            ->when($payload['for_vote_candidate'] ?? false, function ($query) use ($payload) {
                return $query->forVoteCandidate($payload['for_vote_candidate'] ?? []);
            });

        if ($payload['fail'] ?? false) return $candidatePair->firstOrFail();
        return $candidatePair->first();
    }

    /**
     * Create Election Session
     *
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function create(array $payload): CandidatePair
    {
        return $this->candidatePair->create(
            array_filter([
                'election_session_id' => $payload['election_session_id'] ?? null,
                'first_candidate_name' => $payload['first_candidate_name'] ?? null,
                'second_candidate_name' => $payload['second_candidate_name'] ?? null,
                'description' => $payload['description'] ?? null,
                'image_url' => $payload['image_url'] ?? null,
                'number' => $payload['number'] ?? null,
                'total_vote' => $payload['total_vote'] ?? 0,
            ], isNotNullArrayFilter())
        );
    }

    /**
     * Update Election Session
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function updateByParam(string $param, array $payload): CandidatePair
    {
        $candidatePair = $this->getByParam($param, ['fail' => true]);

        return $this->updateByModel($candidatePair, $payload);
    }

    /**
     * Update Candidate Pair By Model
     *
     * @param \App\Models\User\CandidatePair $candidatePair
     * @param array $payload
     * @return \App\Models\User\CandidatePair
     */
    public function updateByModel(CandidatePair $candidatePair, array $payload): CandidatePair
    {
        $candidatePair->update(
            array_filter([
                'election_session_id' => $payload['election_session_id'] ?? null,
                'first_candidate_name' => $payload['first_candidate_name'] ?? null,
                'second_candidate_name' => $payload['second_candidate_name'] ?? null,
                'description' => $payload['description'] ?? null,
                'image_url' => $payload['image_url'] ?? null,
                'number' => $payload['number'] ?? null,
                'total_vote' => $payload['total_vote'] ?? null,
            ], isNotNullArrayFilter())
        );

        return $candidatePair->refresh();
    }

    /**
     * Increment Total Vote By Model
     *
     * @param \App\Models\User\CandidatePair $candidatePair
     * @param int $amount
     * @return \App\Models\User\CandidatePair
     */
    public function incrementTotalVoteByModel(CandidatePair $candidatePair, int $amount = 1): CandidatePair
    {
        $candidatePair->increment('total_vote', $amount);

        return $candidatePair->refresh();
    }

    /**
     * Bulk Delete Election Sessions
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null
    {
        return $this->candidatePair->whereIn('id', $payload['ids'])->delete();
    }
}
