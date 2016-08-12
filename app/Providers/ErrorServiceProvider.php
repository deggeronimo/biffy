<?php

namespace Biffy\Providers;

use Biffy\Exceptions\UserNotFoundException;
use Exception;
use Illuminate\Contracts\Logging\Log;
use Biffy\Exceptions\InvalidAuthEmailException;
use Biffy\Exceptions\PermissionNotFoundException;
use Biffy\Exceptions\UserMissingPermissionException;
use Biffy\Exceptions\ValidationFailedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Exception\Handler;

class ErrorServiceProvider extends ServiceProvider
{

    public function boot(Handler $handler, Log $log)
    {
        $handler->error(function (Exception $e) use ($log) {
                $log->error($e);
            });

        $handler->error(function (InvalidAuthEmailException $exception) use ($log) {
                return 'You must use a ubreakifix.com email address to access the system';
            }
        );

        $handler->error(function (UserNotFoundException $exception) use ($log) {
                return 'No user account was found for that email address. Please contact support to resolve this issue.';
            });

        $handler->error(function (ModelNotFoundException $exception) use ($log) {
                return Response::api()->errorNotFound();
            }
        );

        $handler->error(function (UserMissingPermissionException $exception) use ($log) {
                return Response::api()->errorForbidden();
            }
        );

        $handler->error(function (PermissionNotFoundException $exception) use ($log) {
                return Response::api()->errorInternal();
            }
        );

        $handler->error(function (ValidationFailedException $exception) use ($log) {
                return Response::api()->withError('Validation failed', 422);
            }
        );

        $handler->error(function (\Google_Service_Exception $exception) use ($log) {
                // todo log (notice level?)
//                var_dump($exception->getCode(), $exception->getMessage(), $exception->getErrors());
                return Response::api()->errorInternal();
            }
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}