<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        OAuthServerException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $e
     * @return void
     * @throws \Throwable
     */
    public function report(\Throwable $e): void
    {
        $statusCode = isHttpStatusCodeValid(is_int($e->getCode()) ? $e->getCode() : 0)
            ? $e->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        if (isHttpStatusCodeError($statusCode) && isHttpStatusCodeServerError($statusCode))
            parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, \Throwable $e): JsonResponse|Response
    {
        if (
            $request->is('v1/*') &&
            (
                $e instanceof ModelNotFoundException ||
                $e instanceof \Throwable ||
                $e instanceof ValidationException ||
                $e instanceof NotFoundHttpException ||
                $e instanceof UnauthorizedException ||
                ($e instanceof AuthenticationException && $e->getMessage() == "Unauthenticated.") ||
                $e instanceof MethodNotAllowedHttpException ||
                $e instanceof HttpResponseException
            )
        ) {
            return $this->customApiResponse($e, $request);
        }

        return parent::render($request, $e);
    }

    /**
     * Return custom API response based on exception class instance
     *
     * @param \Throwable $e
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function customApiResponse(\Throwable $e, $request): JsonResponse
    {
        $errors = [];

        if ($e instanceof ModelNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            $message = 'Your request item not found' ?? Response::$statusTexts[Response::HTTP_NOT_FOUND];
        } else if ($e instanceof ValidationException && $request->expectsJson()) {
            $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            $message = Arr::first(Arr::flatten($e->errors()));
            $errors = $e->validator->getMessageBag();
        } else if ($e instanceof NotFoundHttpException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            $message = Response::$statusTexts[Response::HTTP_NOT_FOUND];
        } else if ($e instanceof UnauthorizedException || $e instanceof HttpResponseException) {
            $statusCode = isHttpStatusCodeValid($e->getCode()) ? $e->getCode() : Response::HTTP_UNAUTHORIZED;
            $message = $e->getMessage() ?? Response::$statusTexts[Response::HTTP_UNAUTHORIZED];
        } else if ($e instanceof AuthenticationException) {
            $statusCode = isHttpStatusCodeValid($e->getCode()) ? $e->getCode() : Response::HTTP_FORBIDDEN;
            $message = $e->getMessage() ?? Response::$statusTexts[Response::HTTP_FORBIDDEN];
        } else if ($e instanceof MethodNotAllowedHttpException) {
            $statusCode = isHttpStatusCodeValid($e->getCode()) ? $e->getCode() : Response::HTTP_METHOD_NOT_ALLOWED;
            $message = $e->getMessage() ?? Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED];
        } else if ($e instanceof TooManyRequestsHttpException) {
            $statusCode = isHttpStatusCodeValid($e->getCode()) ? $e->getCode() : Response::HTTP_TOO_MANY_REQUESTS;
            $message = $e->getMessage() ?? Response::$statusTexts[Response::HTTP_TOO_MANY_REQUESTS];
        } else {
            $statusCode = isHttpStatusCodeValid($e->getCode()) ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $e->getMessage() ?? Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];
        }

        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR)
            $message = Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR];

        return response()->json([
            'message' => $message,
            'data' => null,
            'errors' => !empty($errors) ? $errors : null
        ], $statusCode);
    }
}
