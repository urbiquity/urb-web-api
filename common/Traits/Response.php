<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 10/18/18
 * Time: 2:22 PM
 */

namespace Common\Traits;

trait Response
{
    // region Flags
    protected $as_json = false;
    // endregion Flags

    protected $code = 500,
    $title = "Unauthorized action",
    $description = "",
    $meta = [],
    $parameters = [];

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    public function addMeta($data = [])
    {
        if (!empty($data) && is_array($data)) {
            $this->meta = array_merge($this->meta, $data);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;

    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    protected function buildTitle($options = [])
    {
        if (!empty($options)) {
            $this->setResponse($options);
        }

        return [
            "code" => $this->code,
            "title" => $this->title,
            "description" => $this->description,
            "meta" => $this->meta,
            "parameters" => $this->parameters,
        ];
    }

    public function setResponse($options)
    {
        $options = (array) $options;

        $this->code = (isset($options['code']) ? $options['code'] : $this->code);
        $this->title = (isset($options['title']) ? $options['title'] : $this->title);
        $this->description = (isset($options['description']) ? $options['description'] : $this->description);
        $this->meta = (isset($options['meta']) ? $options['meta'] : $this->meta);
        $this->parameters = (isset($options['parameters']) ? $options['parameters'] : $this->parameters);

        return $this;
    }

    public function getResponse($options = [])
    {
        if ($this->as_json === true) {
            return response()->json($this->buildTitle());
        }

        return $this->buildTitle($options);
    }

    public function asJson($flag = true)
    {
        $this->as_json = $flag;

        return $this;
    }

    //region Behavioral

    /**
     * @param Response $other
     * @return Response
     */
    public function absorb($other)
    {
        return $this->setResponse($other->getResponse());
    }

    public function isError()
    {
        return !$this->isSuccess();
    }

    public function isSuccess()
    {
        return ($this->code >= 200 && $this->code <= 299);
    }
    //endregion Behavioral

    //region Success Response
    public function httpSuccessResponse($options = [])
    {
        $options = (array) $options;

        $options['code'] = 200;
        $options['title'] = (isset($options['title']) && \strlen($options['title']) > 0) ? $options['title'] : 'Request successfully executed.';

        return $this->setResponse($options);
    }
    //endregion Success Response

    //region Error Responses

    /**
     * Http 401 Error Response.
     *
     * @return void
     */
    public function httpUnauthorizedResponse($options = [])
    {
        $options = (array) $options;

        $options['code'] = 401;
        $options['title'] = (isset($options['title']) && \strlen($options['title']) > 0) ? $options['title'] : 'Unauthorized access.';

        return $this->setResponse($options);
    }

    /**
     * Http 403 Error Response.
     *
     * @return void
     */
    public function httpForbiddenResponse($options = [])
    {
        $options = (array) $options;

        $options['code'] = 403;
        $options['title'] = (isset($options['title']) && \strlen($options['title']) > 0) ? $options['title'] : 'Forbidden.';

        return $this->setResponse($options);
    }

    /**
     * Http 404 Error Response.
     *
     * @return void
     */
    public function httpNotFoundResponse($options = [])
    {
        $options = (array) $options;

        $options['code'] = 404;
        $options['title'] = (isset($options['title']) && \strlen($options['title']) > 0) ? $options['title'] : 'No data found.';

        return $this->setResponse($options);
    }

    /**
     * Http 500 Error Response.
     *
     * @return void
     */
    public function httpInternalServerResponse($options = [])
    {
        $options = (array) $options;

        $options['code'] = 500;
        $options['title'] = (isset($options['title']) && \strlen($options['title']) > 0) ? $options['title'] : 'Internal server error.';

        return $this->setResponse($options);
    }
    //endregion Error Responses

}
