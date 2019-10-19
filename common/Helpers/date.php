<?php

if (!function_exists("validate_date")) {
    /**
     * Validate a given date.
     *
     * @return mixed
     */
    function validate_date($date)
    {
        $tempDate = explode('-', $date);
        try {
            // checkdate(month, day, year)
            return checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
        } catch (\Exception $e) {
            return false;
        }
    }
}
