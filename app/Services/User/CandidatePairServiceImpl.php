<?php

namespace App\Services\User;

use App\Helpers\Model\CandidatePairHelper;
use App\Models\User\CandidatePair;
use App\Repositories\User\CandidatePairRepository;
use App\Services\File\FileService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

readonly class CandidatePairServiceImpl implements CandidatePairService
{
    public function __construct(
        private FileService             $fileService,
        private CandidatePairRepository $candidatePairRepo
    )
    {
        //
    }

    /**
     * Get All Candidate Pairs
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\Paginator
     */
    public function getAllCandidatePairs(Request $request): Collection|Paginator
    {
        return $this->candidatePairRepo->getAll([
            'paginate' => $request->input('paginate') == 'true',
            'per_page' => $request->input('per_page'),
            'page' => $request->input('page'),
            'election_session_id' => $request->input('election_session_id')
        ]);
    }

    /**
     * Get Candidate Pair By Param
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\CandidatePair|null
     */
    public function getCandidatePairByParam(Request $request, string $param): CandidatePair|null
    {
        return $this->candidatePairRepo->getByParam(
            $param,
            array_merge(
                [
                    'fail' => $request->fail ?? false,
                ],
            // Custom Filters

            )
        );
    }

    /**
     * Get Candidate Pair For Vote Candidate
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \App\Models\User\CandidatePair|null
     */
    public function getCandidatePairForVoteCandidate(Request $request, string $param): CandidatePair|null
    {
        return $this->candidatePairRepo->getByParam(
            $param, [
                'for_vote_candidate' => [
                    'election_session_id' => $request->input('election_session_id')
                ],
                'fail' => $request->fail ?? false
            ]
        );
    }

    /**
     * Create Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function createCandidatePair(Request $request): void
    {
        // Upload image
        $filePathsUploaded = $this->fileService->uploadFilesIntoStorage([
            [
                'file' => $request->file('image'),
                'path' => CandidatePairHelper::STORAGE_PATH,
                'disk' => config('filesystems.disks.public.name'),
                'name' => $this->generateCandidatePairImageName($request->all())
            ]
        ]);

        // Create candidate pair
        try {
            DB::beginTransaction();

            $this->candidatePairRepo->create([
                'election_session_id' => $request->input('election_session_id'),
                'name' => $request->input('name'),
                'first_candidate_name' => $request->input('first_candidate_name'),
                'second_candidate_name' => $request->input('second_candidate_name'),
                'description' => $request->input('description'),
                'image_url' => !empty($filePathsUploaded) ? $filePathsUploaded[0] : null,
                'number' => $request->input('number')
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function updateCandidatePairByParam(Request $request, string $param): void
    {
        // Get candidate pair
        $candidatePair = $this->getCandidatePairByParam($request, $param);
        $oldImageUrl = $candidatePair->image_url;

        // Upload image
        $filePathsUploaded = [];
        $requestHasFileImage = $request->hasFile('image');

        if ($requestHasFileImage) {
            $filePathsUploaded = $this->fileService->uploadFilesIntoStorage([
                [
                    'file' => $request->file('image'),
                    'path' => CandidatePairHelper::STORAGE_PATH,
                    'disk' => config('filesystems.disks.public.name'),
                    'name' => $this->generateCandidatePairImageName($request->all())
                ]
            ]);
        }

        // Update candidate pair
        try {
            DB::beginTransaction();

            $this->candidatePairRepo->updateByModel($candidatePair, [
                'election_session_id' => $request->input('election_session_id'),
                'name' => $request->input('name'),
                'first_candidate_name' => $request->input('first_candidate_name'),
                'second_candidate_name' => $request->input('second_candidate_name'),
                'description' => $request->input('description'),
                'image_url' => !empty($filePathsUploaded) ? $filePathsUploaded[0] : null,
                'number' => $request->input('number')
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        // Delete old image
        if ($requestHasFileImage) {
            $this->fileService->deleteFilesFromStorage([
                [
                    'disk' => config('filesystems.disks.public.name'),
                    'path' => $oldImageUrl
                ]
            ]);
        }
    }

    /**
     * Increment Total Vote For Vote Candidate
     *
     * @param Request $request
     * @param string $param
     * @return void
     * @throws \Throwable
     */
    public function incrementTotalVoteForVoteCandidate(Request $request, string $param): void
    {
        $candidatePair = $this->getCandidatePairForVoteCandidate($request, $param);
        if (!$candidatePair)
            throw new \Exception(trans('candidate_pair.not_found'), Response::HTTP_BAD_REQUEST);

        try {
            DB::beginTransaction();

            $this->candidatePairRepo->incrementTotalVoteByModel($candidatePair);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Bulk Delete Candidate Pair
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Throwable
     */
    public function bulkDeleteCandidatePairs(Request $request): void
    {
        try {
            $candidatePairs = $this->candidatePairRepo->getAll([
                'ids' => $request->input('ids'),
                'select' => [
                    'id', 'image_url'
                ]
            ]);

            DB::beginTransaction();

            $this->candidatePairRepo->bulkDelete([
                'ids' => $candidatePairs->pluck('id')->toArray()
            ]);

            $this->fileService->deleteFilesFromStorage(
                $candidatePairs->map(function ($candidatePair) {
                    return [
                        'disk' => config('filesystems.disks.public.name'),
                        'path' => $candidatePair->image_url
                    ];
                })
                    ->toArray()
            );

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate Candidate Pair Image Name
     *
     * @param array $payload
     * @return string
     */
    private function generateCandidatePairImageName(array $payload): string
    {
        return Str::lower(
            ($payload['first_candidate_name'] ?? '') . '_' . ($payload['second_candidate_name']) . '_' . uniqid() .
            '.' . $payload['image']->getClientOriginalExtension()
        );
    }
}
