<?php

use App\Traits\HttpResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use League\Config\Exception\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'e_app/api/v1/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {

                $responder = new class
                {
                    use HttpResponses;
                };

                return match (true) {
                    $e instanceof AuthenticationException => $responder->error(null, 'Unauthenticated', 401),

                    $e instanceof ValidationException => $responder->error(null, $e->getMessage(), 422),

                    $e instanceof NotFoundHttpException => $responder->error(null, $e->getMessage() ?: 'Resource not found', 404),
                    $e instanceof HttpException => $responder->error(null, $e->getMessage(), $e->getStatusCode()),

                    default => $responder->
                        error(
                            null,
                            config('app.debug') ? $e->getMessage() : 'Something went wrong',
                            500
                        ),

                };
            }
        });
    })->create();
