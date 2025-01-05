<?php

namespace App\Http\Controllers\User;

use App\Helpers\Model\VoterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Voter\BulkDeleteVoterRequest;
use App\Http\Requests\Voter\CreateVoterRequest;
use App\Http\Requests\Voter\UpdateVoterRequest;
use App\Http\Requests\Voter\VoteCandidateRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\Voter\VoterForGeneralResource;
use App\Services\User\VoterService;
use App\Traits\ControllerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoterController extends Controller
{
    use ControllerResource;

    public function __construct(private readonly VoterService $voterService)
    {
        $this->setResourceKey(VoterHelper::RESOURCE_KEY_NAME);
    }

    /**
     * Get Voters
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'message' => trans('resource.get_data_success', [
                'name' => $this->resourceKeysName
            ]),
            'data' => new BaseResourceCollection(
                $this->voterService->getAllVoters($request),
                VoterForGeneralResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get One Voter
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, string $param): JsonResponse
    {
        return response()->json([
            'message' => trans('resource.get_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => new VoterForGeneralResource(
                $this->voterService->getVoterByParam($request, $param)
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Create Voter
     *
     * @param \App\Http\Requests\Voter\CreateVoterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateVoterRequest $request): JsonResponse
    {
        $voter = $this->voterService->createVoter($request);

        return response()->json([
            'message' => trans('resource.create_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => [
                'otp' => $voter->otp
            ],
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Check Ongoing And Available Otp
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOngoingAndAvailableOtp(Request $request): JsonResponse
    {
        $this->voterService->checkOngoingAndAvailableOtp($request);

        return response()->json([
            'message' => trans('voter.otp_can_be_used', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Vote Candidate
     *
     * @param \App\Http\Requests\Voter\VoteCandidateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function voteCandidate(VoteCandidateRequest $request): JsonResponse
    {
        $this->voterService->voteCandidate($request);

        return response()->json([
            'message' => trans('voter.vote_candidate_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Update Voter
     *
     * @param \App\Http\Requests\Voter\UpdateVoterRequest $request
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateVoterRequest $request, string $param): JsonResponse
    {
        $this->voterService->updateVoterByParam($request, $param);

        return response()->json([
            'message' => trans('resource.update_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Bulk Delete Voters
     *
     * @param \App\Http\Requests\Voter\BulkDeleteVoterRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(BulkDeleteVoterRequest $request): JsonResponse
    {
        $this->voterService->bulkDeleteVoters($request);

        return response()->json([
            'message' => trans('resource.delete_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }
}
