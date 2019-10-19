<?php

if( !function_exists("resolve_data") ){
    function resolve_data( $data=null, $target="", $default="" ){

        if( isset( $data[ $target ] ) &&
            !is_null( $data[ $target ] )
        ){
            return $data[ $target ];
        }

        return $default;
    }
}

if( !function_exists( 'object_has_trait') ){
    function object_has_trait( $trait, $object ){
        return in_array( $trait, class_uses_recursive( $object ));
    }
}