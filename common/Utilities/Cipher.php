<?php


namespace Common\Utilities;


class Cipher
{
    protected static $salt = "traxiontech.net";

    public static function hash( $value ){
        return app_hash( self::$salt . $value );
    }
}