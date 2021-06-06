<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Laravel\Passport\Exceptions\OAuthServerException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Application exceptions handler class.
 * 
 * @author vitorg_s@hotmail.com <vitorg_s@hotmail.com>
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected function getStatusCode(Throwable $exception)
    {
        if ($exception instanceof OAuthServerException 
            || $exception instanceof AuthenticationException
        ) {
            return 401;
        }

        $statusCode = $this->isHttpException($exception)
            ? $exception->getCode()
            : $exception->getCode();
        return $statusCode < 200 || $statusCode > 500 ? 500 : $statusCode;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception) {
            $statusCode = $this->getStatusCode($exception);
            return response(
                [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
                $statusCode
            );
        }

        return parent::render($request, $exception);
    }
}
