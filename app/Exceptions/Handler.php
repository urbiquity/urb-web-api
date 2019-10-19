<?php

namespace App\Exceptions;

use Common\Traits\Instances\Response;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            $response = new Response();
            switch($exception->getStatusCode()){
                case 404:
                    $response = $response->httpNotFoundResponse();
                    break;
                case 500:
                    $response = $response->httpInternalServerResponse();
                    break;
            }
            return $response->asJson()->getResponse();
        }

        return parent::render($request, $exception);
    }
}
