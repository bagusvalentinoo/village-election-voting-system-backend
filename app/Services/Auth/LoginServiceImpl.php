<?php

namespace App\Services\Auth;

use App\Models\User\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

readonly class LoginServiceImpl implements LoginService
{
    public function __construct(private UserRepository $userRepo)
    {
        //
    }

    /**
     * Login
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function login(Request $request): array
    {
        $user = $this->userRepo->findForLogin($request->only('username'));
        if (!$user) {
            throw new \Exception(
                trans('auth.incorrect_credentials'),
                Response::HTTP_BAD_REQUEST
            );
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            throw new \Exception(
                trans('auth.incorrect_credentials'),
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            DB::beginTransaction();

            $token = $user->createToken('auth_token');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'user' => $user,
            'bearer_token' => $token->accessToken,
            'expired_at' => $token->token->expires_at->format(config('app.timestamp_format'))
        ];
    }

    /**
     * Logout
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function logout(Request $request): void
    {
        $user = $request->user();
        if (!$user) return;

        try {
            DB::beginTransaction();

            $user->tokens()->update([
                'revoked' => true
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get user logged in
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User\User
     */
    public function getUserLoggedIn(Request $request): User
    {
        return $request->user();
    }
}
