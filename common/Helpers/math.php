<?php

use Carbon\Carbon;

if( !function_exists( "app_hash" ) ){
    /**
     * Creates a "app" hash to prevent ID tampering attack
     *
     * @param $value
     * @param string $func
     * @return string
     */
    function app_hash( $value, $func="sha1" ){
        $timestamp = Carbon::now()->format("Ym");
        $app = $value . $timestamp;

        return quick_hash( $app, 0, 0, $func );
    }
}

if (!function_exists("between")) {
    /**
     * Check if value is in between.
     *
     * @param float $value
     * @param float $min
     * @param float $max
     * @return boolean
     */
    function between($value, $min, $max)
    {
        return ($min <= $value) && ($value <= $max);
    }
}

if (!function_exists("nf")) {
    /**
     * Creates a number format from a given value.
     *
     * @param integer $value
     * @param integer $places
     * @return number
     */
    function nf($value = 0, $places = 2)
    {
        return number_format($value, $places);
    }
}

if( !function_exists( "percentage") ){
    function percentage( $count, $base ){
        return ($count/$base) * 100;
    }
}

if (!function_exists("quick_hash")) {
    /**
     * Creates a quick hash from a value
     *
     * @param string $value
     * @param integer $from
     * @param integer $to
     * @param string $func
     * @return string
     */
    function quick_hash($value, $from = 0, $to = 25, $func='sha256')
    {
        if( $from === 0 && $to === 0 ){
            return $func($value);
        }
        return substr($func($value), $from, $to);
    }
}

if (!function_exists("round_value")) {
    /**
     * Round up value with precision.
     *
     * @param mixed $value refers to the value beign rounded up
     * @param integer $precision refers to the precision of decimal places to be rounded up
     * @param boolean $mode true if round up else round down
     * @return mixed
     */
    function round_value($value, $precision = 5, $mode = true)
    {
        $computed = ($value * pow(10, $precision));
        return ($mode == true ? ceil($computed) : floor($computed)) / pow(10, $precision);
    }

}

if( !function_exists( "sessioned_hash" ) ){
    /**
     * Creates a "sessioned" hash to prevent ID tampering attack
     *
     * @param $value
     * @param string $func
     * @return string
     */
    function sessioned_hash( $value, $func="sha1" ){
        $salt = env("SESSIONED_HASH_SALT", common_config( "session_salt" ) );
        $timestamp = Carbon::now()->format("YmW");
        $sessioned = $value . $salt . $timestamp;

        return quick_hash( $sessioned, 0, 0, $func );
    }
}

if( !function_exists( "validate_sessioned_hash") ){
    /**
     * Validates a provided value with a given hash
     *
     * @param $value
     * @param $hash
     * @param string $func
     * @return bool
     */
    function validate_sessioned_hash( $value, $hash, $func="sha1" ){
        return sessioned_hash( $value, $func ) === $hash;
    }
}

if( !function_exists( "validate_app_hash") ){
    /**
     * Validates a provided value with a given hash
     *
     * @param $value
     * @param $hash
     * @param string $func
     * @return bool
     */
    function validate_app_hash( $value, $hash, $func="sha1" ){
        return app_hash( $value, $func ) === $hash;
    }
}