<?php

namespace App\Http\Controllers\Election;

use App\Helpers\Model\ElectionSessionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ElectionSession\BulkDeleteElectionSessionRequest;
use App\Http\Requests\ElectionSession\CreateElectionSessionRequest;
use App\Http\Requests\ElectionSession\UpdateElectionSessionRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\ElectionSession\ElectionSessionForGeneralResource;
use App\Http\Resources\ElectionSession\ElectionSessionForResultResource;
use App\Http\Resources\ElectionSession\ElectionSessionForVotingResource;
use App\Services\Election\ElectionSessionService;
use App\Traits\ControllerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ElectionSessionController extends Controller
{
    use ControllerResource;

    public function __construct(private readonly ElectionSessionService $electionSessionService)
    {
        $this->setResourceKey(ElectionSessionHelper::RESOURCE_KEY_NAME);
    }

    /**
     * Get Election Sessions
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
                $this->electionSessionService->getAllElectionSessions($request),
                ElectionSessionForGeneralResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get Ongoing Election Sessions For Voting
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOngoingForVoting(Request $request): JsonResponse
    {
        return response()->json([
            'message' => trans('resource.get_data_success', [
                'name' => $this->resourceKeysName
            ]),
            'data' => new BaseResourceCollection(
                $this->electionSessionService->getAllElectionSessions(
                    $request->merge(['for_ongoing_voting' => []])
                ),
                ElectionSessionForVotingResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get Ongoing Election Sessions For Result
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOngoingForResult(Request $request): JsonResponse
    {
        return response()->json([
            'message' => trans('resource.get_data_success', [
                'name' => $this->resourceKeysName
            ]),
            'data' => new BaseResourceCollection(
                $this->electionSessionService->getAllElectionSessions(
                    $request->merge(['for_ongoing_result' => []])
                ),
                ElectionSessionForResultResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get One Election Session
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
            'data' => new ElectionSessionForGeneralResource(
                $this->electionSessionService->getElectionSessionByParam($request, $param)
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Create Election Session
     *
     * @param \App\Http\Requests\ElectionSession\CreateElectionSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateElectionSessionRequest $request): JsonResponse
    {
        $this->electionSessionService->createElectionSession($request);

        return response()->json([
            'message' => trans('resource.create_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Create Election Session
     *
     * @param \App\Http\Requests\ElectionSession\UpdateElectionSessionRequest $request
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateElectionSessionRequest $request, string $param): JsonResponse
    {
        $this->electionSessionService->updateElectionSessionByParam($request, $param);

        return response()->json([
            'message' => trans('resource.update_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Bulk Delete Election Sessions
     *
     * @param \App\Http\Requests\ElectionSession\BulkDeleteElectionSessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BulkDeleteElectionSessionRequest $request): JsonResponse
    {
        $this->electionSessionService->bulkDeleteElectionSessions($request);

        return response()->json([
            'message' => trans('resource.delete_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }
}
