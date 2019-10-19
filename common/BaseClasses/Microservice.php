<?php


namespace Common\BaseClasses;


use Common\Traits\Response;
use Common\Utilities\Cipher;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

abstract class Microservice extends Host
{
    protected $app_slug, $method, $urls = [];

    public function __construct($options = [], $url = "")
    {
        $this->urls = json_decode(
                file_get_contents( $this->getCurrentDir() . "/urls.json" )
            , true );

        parent::__construct($options, $url);
    }

    public function call( $slug, $data, $headers = [] )
    {

        $this->buildHeaders( $headers );

        $url = $this->makeUrlFromSlug( $slug, $data, $this->method );
        $method = $this->method;

        $debug = false;

        if( isset($data['__debug']['dump_url']) && $data['__debug']['dump_url'] === true ){
            var_dump( $url );
        }

        if( isset($data['__debug']['dump_params']) && $data['__debug']['dump_params'] === true ){
            var_dump( $data );
        }

        if( $debug === true ){
            exit();
        }

        try {

            $result = $this->$method( $url, $data, $this->headers );


            if( isset($data['__debug']['dump_result']) && $data['__debug']['dump_result'] === true ){
                var_dump( $result );
            }


            if (object_has_trait(Response::class, $result)) {
                return $this->absorb($result);
            }

            $code = 500;
            $title = "Successfully called microservice";
            $description = "";
            $meta = [];
            $parameters = [];

            if (isset($result->code)) {
                $code = $result->code;
            }

            if (isset($result->title)) {
                $title = trim($result->title) == "" ?: $result->title;
            }

            if (isset($result->parameters)) {
                $parameters = $result->parameters;
            }

            if (isset($result->description)) {
                $description = trim($result->description) == "" ?: $result->description;
            }

            if ($result->meta) {
                $meta = $result->meta;
            }

            $this->setResponse([
                'code' => $code,
                'title' => $title,
                'description' => $description,
                'meta' => array_merge((array)$meta, [

                ]),
                'parameters' => array_merge((array)$parameters, [
                    "request" => [
                        "method" => $method,
                        "url" => $url,
                    ],
                    "data" => $data,
                ])
            ]);

        } catch (\Exception $exception) {
            dd($exception);
        }

        return $this;
    }

    protected function buildHeaders($headers = [])
    {
        $this->headers["Security-Source-Name"] = env("APP_SLUG");
        $this->headers["Security-Source-Hash"] = Cipher::hash(config(env("APP_SLUG")));
        $this->headers["Security-Target-Hash"] = Cipher::hash(config($this->app_slug));

        $this->headers = array_merge($this->headers, $headers);
    }

    protected function makeUrlFromSlug( $slug, $data = [], &$method )
    {
        $urls = $this->urls;
        $slug = explode( '.', $slug );

        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator( (array) $urls ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $array = [ $urls['prefix'] ];
        foreach ( $slug as $slug_ ) {
            foreach ( $iterator as $key => $value ) {

                // Check whether slug exists in urls
                if ( $key == 'slug' && $value == $slug_ ) {

                    // Get every child element
                    $child = (array) $iterator->getInnerIterator();

                    $array[] = $child[ 'prefix' ];
                    if( isset( $child['method'] ) ){
                        $method = $child[ 'method' ];

                    }

                }

            }
        }

        $result = "/" . implode( "/", array_unique( $array ) );

        // Replace URL-based data
        if( $method === 'GET' ){
            foreach( $data as $key => $datum ){
                if ( str_contains( $key, "__" ) || is_array( $datum ) ) {
                    continue;
                }
                $result = str_replace( "{" . $key . "}", $datum, $result );
            }
        }

        return $result;
    }

    // Deprecated
    protected function buildUrlFromSlug($slug, $data = [])
    {
        $url = $this->dot_urls[$slug];
        $method = $this->getMethodFromSlug($slug);

        // Replace URL-based data
        if ($method == 'get') {
            foreach ($data as $key => $datum) {
                $url = str_replace("{" . $key . "}", $datum, $url);
            }
        }

        return (substr($url, 0, 1) === "/" ? "" : "/") . $url;
    }

    protected function getCurrentDir()
    {
        $reflector = new \ReflectionClass(get_class($this));
        return dirname($reflector->getFileName());
    }

}
