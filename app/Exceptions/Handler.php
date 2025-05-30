<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            logger($e);
        });
    }
    public function render($request, Throwable $exception)
{
    Log::info('Redirecting or Exception:', [
        'path' => $request->path(),
        'exception' => get_class($exception),
        'message' => $exception->getMessage(),
    ]);

    return parent::render($request, $exception);
}

}
