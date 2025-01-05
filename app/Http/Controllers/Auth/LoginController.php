<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserForLoginResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function __construct(private readonly LoginService $loginService)
    {
        //
    }

    /**
     * Login
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginData = $this->loginService->login($request);

        return response()->json([
            'message' => trans('auth.login_success'),
            'data' => [
                'user' => new UserForLoginResource($loginData['user']),
                'bearer_token' => $loginData['bearer_token'],
                'expired_at' => $loginData['expired_at']
            ],
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Logout
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->loginService->logout($request);

        return response()->json([
            'message' => trans('auth.logout_success'),
            'data' => null,
            'errors' => null
        ], Response::HTTP_OK);
    }

    /**
     * Get user logged in
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserLoggedIn(Request $request): JsonResponse
    {
        $user = $this->loginService->getUserLoggedIn($request);

        return response()->json([
            'message' => trans('resource.get_data_success'),
            'data' => new UserForLoginResource($user),
            'errors' => null
        ], Response::HTTP_OK);
    }
}
