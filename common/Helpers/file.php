<?php

if (!function_exists("route_path")) {
    /**
     * Returns the absolute path of a given file name.
     *
     * @param string $path
     * @return void
     */
    function route_path($path)
    {
        $path = str_replace(".", DIRECTORY_SEPARATOR, $path) . ".php";
        return base_path($path);
    }
}

if( !function_exists('common_config') ){
    /**
     * Returns common's configuration file with specified key
     *
     * @param $key
     * @param string $file
     * @return mixed
     */
    function common_config( $key, $file="common" ){
        $config = require base_path( "common/config/" . $file . ".php" );

        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($config));
        $result = [];
        foreach ($iterator as $leafValue) {
            $keys = array();

            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }

            $result[ join('.', $keys) ] = $leafValue;
        }

        return isset( $result[ $key ] ) ? $result[ $key ] : null;
    }
}

if( !function_exists("class_of_model") ){
    function class_of_model( $slug, $invoke=false ){
        $model = common_config( "classmap.models." . $slug );
        return $invoke ? new $model() : $model;
    }
}

if( !function_exists('get_class_from_file') ){
    function get_class_from_file($path_to_file)
    {
        //Grab the contents of the file
        $contents = file_get_contents($path_to_file);

        //Start with a blank namespace and class
        $namespace = $class = "";

        //Set helper values to know that we have found the namespace/class token and need to collect the string values after them
        $getting_namespace = $getting_class = false;

        //Go through each token and evaluate it as necessary
        foreach (token_get_all($contents) as $token) {

            //If this token is the namespace declaring, then flag that the next tokens will be the namespace name
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            //If this token is the class declaring, then flag that the next tokens will be the class name
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            //While we're grabbing the namespace name...
            if ($getting_namespace === true) {

                //If the token is a string or the namespace separator...
                if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {

                    //Append the token's value to the name of the namespace
                    $namespace .= $token[1];

                }
                else if ($token === ';') {

                    //If the token is the semicolon, then we're done with the namespace declaration
                    $getting_namespace = false;

                }
            }

            //While we're grabbing the class name...
            if ($getting_class === true) {

                //If the token is a string, it's the name of the class
                if(is_array($token) && $token[0] == T_STRING) {

                    //Store the token's value as the class name
                    $class = $token[1];

                    //Got what we need, stope here
                    break;
                }
            }
        }

        //Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;

    }
}

if( !function_exists("read_csv") ) {
    function read_csv( $file_path, $options )
    {
        $line_of_text = [];
        $file_handle = fopen( $file_path, 'r' );
        while ( !feof( $file_handle ) ) {
            $line_of_text[] = fgetcsv( $file_handle, 0, $options[ 'delimiter' ] );
        }
        fclose( $file_handle );
        return $line_of_text;
    }
}