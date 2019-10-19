<?php

namespace Common\BaseClasses;

use Common\Traits\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Support\Facades\Request;

/**
 * Class Controller
 * @package App\Http\Controllers
 *
 * @function all() Retrieves all related data of a specific definition
 */
abstract class Controller extends LaravelController
{
    use Response, AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Set response header code.
     *
     * @return void
     */
    public function json()
    {
        $code = 200;

        if (!Request::has("no_response_code") || Request::input('no_response_code') != "yes") {
            $code = $this->getCode();
        }

        return response()->json($this->getResponse(), $code);
    }
}
