<?php

namespace App\Http\Controllers\User;

use App\Helpers\Model\CandidatePairHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidatePair\BulkDeleteCandidatePairRequest;
use App\Http\Requests\CandidatePair\CreateCandidatePairRequest;
use App\Http\Requests\CandidatePair\UpdateCandidatePairRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\CandidatePair\CandidatePairForGeneralResource;
use App\Services\User\CandidatePairService;
use App\Traits\ControllerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidatePairController extends Controller
{
    use ControllerResource;

    public function __construct(private readonly CandidatePairService $candidatePairService)
    {
        $this->setResourceKey(CandidatePairHelper::RESOURCE_KEY_NAME);
    }

    /**
     * Get Candidate Pairs
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
                $this->candidatePairService->getAllCandidatePairs($request),
                CandidatePairForGeneralResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get One Candidate Pair
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
            'data' => new CandidatePairForGeneralResource(
                $this->candidatePairService->getCandidatePairByParam($request, $param)
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Create Election Session
     *
     * @param \App\Http\Requests\CandidatePair\CreateCandidatePairRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCandidatePairRequest $request): JsonResponse
    {
        $this->candidatePairService->createCandidatePair($request);

        return response()->json([
            'message' => trans('resource.create_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Create Candidate Pair
     *
     * @param \App\Http\Requests\CandidatePair\UpdateCandidatePairRequest $request
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCandidatePairRequest $request, string $param): JsonResponse
    {
        $this->candidatePairService->updateCandidatePairByParam($request, $param);

        return response()->json([
            'message' => trans('resource.update_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Bulk Delete Candidate Pairs
     *
     * @param \App\Http\Requests\CandidatePair\BulkDeleteCandidatePairRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(BulkDeleteCandidatePairRequest $request): JsonResponse
    {
        $this->candidatePairService->bulkDeleteCandidatePairs($request);

        return response()->json([
            'message' => trans('resource.delete_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }
}
