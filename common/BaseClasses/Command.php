<?php

namespace Common\BaseClasses;

use Common\Traits\Response;
use Illuminate\Console\Command as LaravelCommand;
use Illuminate\Support\Facades\Request;

/**
 * Class Command
 * @package Common\BaseClasses
 *
 */
abstract class Command extends LaravelCommand
{
    // use Response;


    /**
     * Set response header code.
     *
     * @return void
     */
    public function json( $response )
    {
        return response()->json( $response, 200);
    }
}
