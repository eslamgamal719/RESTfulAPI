<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{

    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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


    public function report(Exception $exception)
    {
        parent::report($exception);
    }



    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }


        if ($exception instanceof ModelNotFoundException) {

            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exist any {$modelName} with the specified identificator", 404);
        }


        if ($exception instanceof AuthenticationException)
        {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), 403);
        }


        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("The specified method for the request is invalid", 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("The specified URL can not be found", 404);
        }


        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        } //end of general exception


        if ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];

            if ($errorCode == 1451) {
                return $this->errorResponse("can't remove this resource permanently. It is related with any other resource", 409);
            }
        } //end of Query exception

        if($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }


        if(config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse("Unexpected Exception. Try Later", 500);


    }




    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse("Unauthenticated.", 401);
    }


    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }


    private function isFrontend($request) {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }


}
