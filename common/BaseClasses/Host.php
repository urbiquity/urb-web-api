<?php

namespace Common\BaseClasses;

use Common\Traits\Response;
use \GuzzleHttp\Client;

abstract class Host
{
    use Response;

    /**
     * @var Client $client
     */
    protected $client;
    protected $config;
    protected $base_url;
    protected $options;
    protected $response_as_json = true;
    protected $response_as_standard = false;
    protected $auth_flag = false;
    protected $file_flag = false;

    protected $headers = [];

    public function __construct($options = [], $url = "")
    {
        $this->initializeClient();
    }

    /**
     * provider for "GET" api calls
     *
     * @param string $url
     * @param array $request
     * @param array $headers
     * @return mixed
     */
    public function get($url, $request = [], $headers = [])
    {

        if ($this->auth_flag === true && isset($request['request'])) {
            $headers = [
                'Authorization' => $request['request']->header('Authorization'),
            ];
        }

        $data = isset($request['request']) ? $request['request']->all() : $request;

        $path = $this->base_url . $url;
        if (!empty($data)) {
            $path .= "?";

            foreach ($data as $key => $value) {

                if (isset($data['target']) && $key == "target") {
                    foreach ((array) $data['target'] as $index => $info) {
                        $path .= "&target[]=" . $info;
                    }
                } else if (!is_array($value)) {
                    $path .= "&" . $key . "=" . $value;
                }
            }
        }

        $options = [
            'headers' => [],
        ];

        if (!empty($headers)) {
            $options['headers'] = array_merge( $options['headers'], $headers );
        }

        $options['headers'] = array_merge( $options['headers'] , $this->headers );

        if (!empty($options)) {
            $this->initializeClient($options);
        }

        return $this->fetchRawResponse('GET', $path, [], true);
    }

    /**
     * fetching raw response from api client
     *
     * @param string $method
     * @param string $path
     * @param array $data
     * @param boolean $generate
     * @return mixed
     *
     * @return \Illuminate\Http\JsonResponse|mixed|object|\Psr\Http\Message\ResponseInterface|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchRawResponse($method, $path, $data = [], $generate = false)
    {
        $response = null;

        if( !empty( $this->headers ) ){
            if( !isset( $data['headers']) ){
                $data['headers'] = [];
            }

            $data['headers'] = array_merge( $data['headers'], $this->headers );
        }

        try {
            $response = $this->client->request($method, $path, $data);
        } catch (\GuzzleHttp\Exception\ServerException $exception) {
            $response = $exception->getResponse();
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            return (object) [
                'code' => 503,
                'title' => $exception->getMessage(),
            ];
        } catch( \Exception $e ){
            dd( $e );
        }

        if ($generate) {
            return $this->generateResponse($response);
        }

        return $response;
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function generateResponse(\GuzzleHttp\Psr7\Response $response)
    {
        $_response = $response;

        $response = $response->getBody()->getContents();

        if ($this->response_as_standard && !is_code_success($response->code)) {
            return response()->json($response);
        }

        if ($this->response_as_json ) {
            if( json_decode($response) !== null ){
                return json_decode($response);
            }

            return $this->setResponse([
                "code" => $_response->getStatusCode(),
                "title" => $_response->getReasonPhrase(),
            ]);
        }

        return $response;
    }

    /**
     * Initiliaze client connection.
     *
     * @param array $options
     * @param string $url
     * @return void
     */
    protected function initializeClient($options = [], $url = "")
    {
        $url = $url === "" ? $this->base_url : $url;
        $options = array_merge($options, $this->options);
        $this->client = new Client(
            array_merge([
                "base_uri" => $url,
            ], $options)
        );
    }

    /**
     * provider for "POST" api calls
     *
     * @param string $url
     * @param array $request
     * @param array $headers
     * @return mixed
     */
    public function post($url, $request = [], $headers = [])
    {
        if ($this->auth_flag === true && isset($request['request'])) {
            $headers = [
                'Authorization' => $request['request']->header('Authorization'),
            ];
        }

        $data = isset($request['request']) ? $request['request']->all() : $request;

        $path = $this->base_url . $url;

        $options = [
            'headers' => [],
        ];

        if (!empty($headers)) {
            $options['headers'] = array_merge( $options['headers'], $headers );
        }

        if (isset($data['headers'])) {
            foreach ($data['headers'] as $key => $value) {
                $options['headers'][$key] = $value;
            }
        }

        $options['headers'] = array_merge( $options['headers'] , $this->headers );

        if (!empty($options)) {
            $this->initializeClient($options);
        }

        if (isset($data['multipart'])) {
            $post_data = [
                'multipart' => $data['multipart'],
            ];

        } else {
            $post_data = [
                'form_params' => $data,
            ];
        }

        return $this->fetchRawResponse('POST', $path, $post_data, true);
    }
}
