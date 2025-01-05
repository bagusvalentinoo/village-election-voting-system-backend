<?php

namespace App\Services\User;

use App\Helpers\Model\VoterHelper;
use App\Models\User\Voter;
use App\Repositories\User\VoterRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

readonly class VoterServiceImpl implements VoterService
{
    public function __construct(
        private VoterRepository      $voterRepo,
        private CandidatePairService $candidatePairService,
    )
    {
        //
    }

    /**
     * Get All Voters
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllVoters(Request $request): Collection|Paginator
    {
        return $this->voterRepo->getAll([
            'paginate' => $request->input('paginate') == 'true',
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page')
        ]);
    }

    /**
     * Get Voter By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\Voter|null
     */
    public function getVoterByParam(Request $request, string $param): Voter|null
    {
        return $this->voterRepo->getByParam($param, [
            'fail' => true
        ]);
    }

    /**
     * Create Voter
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\Voter
     * @throws \Throwable
     */
    public function createVoter(Request $request): Voter
    {
        try {
            DB::beginTransaction();

            $voter = $this->voterRepo->create([
                'election_session_id' => $request->input('election_session_id'),
                'nik' => $request->input('nik'),
                'full_name' => $request->input('full_name'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),
                'otp' => $this->generateUniqueOtp()
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return $voter;
    }

    /**
     * Check Ongoing And Available Otp
     *
     * @param Request $request
     * @return void
     * @throws \Throwable
     */
    public function checkOngoingAndAvailableOtp(Request $request): void
    {
        $voter = $this->voterRepo->getByParam(null, [
            'for_vote_candidate' => [
                'otp' => $request->input('otp')
            ]
        ]);
        if (!$voter)
            throw new \Exception(trans('voter.get_for_vote_candidate_error'), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Vote Candidate
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function voteCandidate(Request $request): void
    {
        // Get Voter
        $voter = $this->voterRepo->getByParam(null, [
            'for_vote_candidate' => [
                'otp' => $request->input('otp')
            ]
        ]);
        if (!$voter) {
            throw new \Exception(
                trans('voter.get_for_vote_candidate_error'), Response::HTTP_BAD_REQUEST
            );
        }

        try {
            DB::beginTransaction();

            $this->candidatePairService->incrementTotalVoteForVoteCandidate(
                makeRequest([
                    'election_session_id' => $voter->election_session_id
                ]),
                $request->input('candidate_pair_id')
            );

            $this->voterRepo->updateByModel($voter, [
                'otp_status' => VoterHelper::OTP_STATUSES['used'],
                'selected_candidate_pair_id' => $request->input('candidate_pair_id')
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update Voter
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateVoterByParam(Request $request, string $param): void
    {
        try {
            DB::beginTransaction();

            $this->voterRepo->updateByParam($param, [
                'nik' => $request->input('nik'),
                'full_name' => $request->input('full_name'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'gender' => $request->input('gender'),
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Bulk Delete Voters
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteVoters(Request $request): void
    {
        try {
            DB::beginTransaction();

            $this->voterRepo->bulkDelete([
                'ids' => $request->input('ids')
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate Unique OTP
     *
     * @return string
     */
    private function generateUniqueOtp(): string
    {
        $otp = Str::upper(Str::random(12));
        if (!$this->voterRepo->isOtpExists($otp)) return $otp;

        return $this->generateUniqueOtp();
    }
}
