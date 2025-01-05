<?php

namespace App\Services\Election;

use App\Models\Election\ElectionSession;
use App\Repositories\Election\ElectionSessionRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

readonly class ElectionSessionServiceImpl implements ElectionSessionService
{
    public function __construct(private ElectionSessionRepository $electionSessionRepo)
    {
        //
    }

    /**
     * Get All Election Sessions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllElectionSessions(Request $request): Collection|Paginator
    {
        return $this->electionSessionRepo->getAll([
            'paginate' => $request->input('paginate') == 'true',
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page'),
            'for_ongoing_voting' => $request->input('for_ongoing_voting'),
            'for_ongoing_result' => $request->input('for_ongoing_result')
        ]);
    }

    /**
     * Get Election Session By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\Election\ElectionSession|null
     */
    public function getElectionSessionByParam(Request $request, string $param): ElectionSession|null
    {
        return $this->electionSessionRepo->getByParam($param, [
            'fail' => true
        ]);
    }

    /**
     * Create Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function createElectionSession(Request $request): void
    {
        try {
            DB::beginTransaction();

            $this->electionSessionRepo->create([
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date')
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateElectionSessionByParam(Request $request, string $param): void
    {
        try {
            DB::beginTransaction();

            $this->electionSessionRepo->updateByParam($param, [
                'name' => $request->input('name'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date')
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Bulk Delete Election Session
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteElectionSessions(Request $request): void
    {
        try {
            DB::beginTransaction();

            $this->electionSessionRepo->bulkDelete([
                'ids' => $request->input('ids')
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
