<?php

/**
 * Is Http Status Code Error
 *
 * @param int $statusCode
 * @return bool
 */
function isHttpStatusCodeError(int $statusCode): bool
{
    return $statusCode >= 400 && $statusCode < 600;
}

/**
 * Is Http Status Code Client Error
 *
 * @param int $statusCode
 * @return bool
 */
function isHttpStatusCodeClientError(int $statusCode): bool
{
    return $statusCode >= 400 && $statusCode < 500;
}

/**
 * Is Http Status Code Server Error
 *
 * @param int $statusCode
 * @return bool
 */
function isHttpStatusCodeServerError(int $statusCode): bool
{
    return $statusCode >= 500 && $statusCode < 600;
}

/**
 * Is Http Status Code Valid
 *
 * @param int|string $statusCode
 * @return bool
 */
function isHttpStatusCodeValid(int|string $statusCode): bool
{
    $statusCode = (int)$statusCode;
    return $statusCode >= 100 && $statusCode < 600;
}

/**
 * Make Request
 *
 * @param array $array_request
 * @param bool $withUserResolver
 * @return \Illuminate\Http\Request
 */
function makeRequest(array $array_request = [], bool $withUserResolver = true): \Illuminate\Http\Request
{
    $request = request()->create(request()->path(), request()->method(), $array_request);

    if ($withUserResolver) {
        $request = $request->setUserResolver(function () use ($array_request) {
            $user = auth()->guard(config('auth.defaults.guard'))->user();
            return $user ?? $array_request['_user_auth'];
        });
    }

    return $request;
}
