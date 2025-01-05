<?php

namespace App\Repositories\User;

use App\Models\User\Voter;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

readonly class VoterRepositoryImpl implements VoterRepository
{
    public function __construct(private Voter $voter)
    {
        //
    }

    /**
     * Get All Voters
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAll(array $payload = []): Collection|Paginator
    {
        if (isset($payload['paginate']) && $payload['paginate'])
            return $this->voter->simplePaginate($payload['per_page'] ?? null);

        return $this->voter->get();
    }

    /**
     * Get Voter By Param
     *
     * @param string|null $param
     * @param array $payload
     * @return \App\Models\User\Voter|null
     */
    public function getByParam(?string $param = null, array $payload = []): Voter|null
    {
        $voter = $this->voter->baseFilter($payload);

        $voter = $voter
            ->when($param, function ($query) use ($param) {
                return $query->where('id', $param);
            })
            ->when($payload['for_vote_candidate'] ?? false, function ($query) use ($payload) {
                return $query->forVoteCandidate($payload['for_vote_candidate'] ?? []);
            });

        if ($payload['fail'] ?? false) return $voter->firstOrFail();
        return $voter->first();
    }

    /**
     * Check If OTP Exists
     *
     * @param string $otp
     * @return bool
     */
    public function isOtpExists(string $otp): bool
    {
        return $this->voter->where('otp', $otp)->exists();
    }

    /**
     * Create Voter
     *
     * @param array $data
     * @return Voter
     */
    public function create(array $data): Voter
    {
        return $this->voter->create(
            array_filter([
                'election_session_id' => $data['election_session_id'] ?? null,
                'nik' => $data['nik'] ?? null,
                'full_name' => $data['full_name'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'address' => $data['address'] ?? null,
                'gender' => $data['gender'] ?? null,
                'otp' => $data['otp'] ?? null,
            ], isNotNullArrayFilter())
        );
    }

    /**
     * Update Voter By Param
     *
     * @param string $param
     * @param array $payload
     * @return \App\Models\User\Voter
     */
    public function updateByParam(string $param, array $payload): Voter
    {
        $voter = $this->getByParam($param, ['fail' => true]);
        return $this->updateByModel($voter, $payload);
    }

    /**
     * Update Voter By Model
     *
     * @param \App\Models\User\Voter $voter
     * @param array $payload
     * @return \App\Models\User\Voter
     */
    public function updateByModel(Voter $voter, array $payload): Voter
    {
        $voter->update(
            array_filter([
                'election_session_id' => $payload['election_session_id'] ?? null,
                'nik' => $payload['nik'] ?? null,
                'full_name' => $payload['full_name'] ?? null,
                'birth_date' => $payload['birth_date'] ?? null,
                'address' => $payload['address'] ?? null,
                'gender' => $payload['gender'] ?? null,
                'otp' => $payload['otp'] ?? null,
                'otp_status' => $payload['otp_status'] ?? null,
                'selected_candidate_pair_id' => $payload['selected_candidate_pair_id'] ?? null,
            ], isNotNullArrayFilter())
        );

        return $voter->refresh();
    }

    /**
     * Bulk Delete Voters
     *
     * @param array $payload
     * @return bool|null
     */
    public function bulkDelete(array $payload): bool|null
    {
        return $this->voter->whereIn('id', $payload['ids'])->delete();
    }
}
