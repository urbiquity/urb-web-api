<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 10/22/18
 * Time: 9:45 AM
 */

namespace Common\Traits\Instances;


class Response
{
    use \Common\Traits\Response;

    private static $_instance;

    public static function respond( $data=[] ){
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return (self::$_instance)->getResponse ( $data );
    }
}
