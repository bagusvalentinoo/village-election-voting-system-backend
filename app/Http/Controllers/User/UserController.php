<?php

namespace App\Http\Controllers\User;

use App\Helpers\Model\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\BulkDeleteUserRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\User\UserForGeneralResource;
use App\Services\User\UserService;
use App\Traits\ControllerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use ControllerResource;

    public function __construct(private readonly UserService $userService)
    {
        $this->setResourceKey(UserHelper::RESOURCE_KEY_NAME);
    }

    /**
     * Get Users
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
                $this->userService->getAllUsers($request),
                UserForGeneralResource::class
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get One User
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
            'data' => new UserForGeneralResource(
                $this->userService->getUserByParam($request, $param)
            ),
            'errors' => null
        ], Response::HTTP_OK);
    }


    /**
     * Create User
     *
     * @param \App\Http\Requests\User\CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $this->userService->createUser($request);

        return response()->json([
            'message' => trans('resource.create_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Update User
     *
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param string $param
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, string $param): JsonResponse
    {
        $this->userService->updateUserByParam($request, $param);

        return response()->json([
            'message' => trans('resource.update_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Bulk Delete Users
     *
     * @param \App\Http\Requests\User\BulkDeleteUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(BulkDeleteUserRequest $request): JsonResponse
    {
        $this->userService->bulkDeleteUsers($request);

        return response()->json([
            'message' => trans('resource.delete_data_success', [
                'name' => $this->resourceKeyName
            ]),
            'data' => null,
            'errors' => null
        ], Response::HTTP_CREATED);
    }
}
