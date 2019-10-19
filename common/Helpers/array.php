<?php

if (!function_exists("array_to_object")) {
    /**
     * @param array $array
     * @return object
     */
    function array_to_object(array $array)
    {
        return (object) $array;
    }
}

if( !function_exists("array_to_dot_notation") ){
    function array_to_dot_notation( $array ){
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator( (array)$array ),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $path = [];
        $flatArray = [];

        foreach ($iterator as $key => $value) {
            $path[$iterator->getDepth()] = $key;

            if (!is_array($value)) {
                $flatArray[
                    implode('.', array_slice($path, 0, $iterator->getDepth() + 1))
                ] = $value;
            }
        }

        return $flatArray;
    }
}

if( !function_exists("is_multidimensional") ){
    function is_multidimensional( $array ){
        return count($array) !== count($array, COUNT_RECURSIVE);
    }
}
