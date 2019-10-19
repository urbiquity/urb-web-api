<?php

namespace App\Http\Controllers\Utilities\Configurations;

use Common\Microservices\Utilities\Configurations\ConfigurationMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class VariableTypeController
 * @package App\Http\Controllers\Utilities\Configurations
 */
class VariableTypeController extends BaseController
{
    protected $config_svc;

    /**
     * Instantiate class with $configurationMicroservice
     *
     * @param ConfigurationMicroservice $configurationMicroservice
     */
    public function __construct(
        ConfigurationMicroservice $configurationMicroservice
    ){
        $this->config_svc = $configurationMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( Request $request )
    {
        return $this->absorb(
            $this->config_svc->call("variables.types.all",
                $request->all()
            )
        )->json();

    }

    //endregion All

    // region Define

    /**
     * @param Request $request
     * @return mixed
     */
    public function define( Request $request )
    {
        return $this->absorb(
            $this->config_svc->call("variables.types.define",
                $request->all()
            )
        )->json();
    }

    // endregion Define

    // region Delete

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function delete( $id )
    {
        return $this->absorb(
            $this->config_svc->call("variables.types.delete.id", [
                "id" => $id,
            ])
        )->json();
    }

    // endregion Delete

    // region Retrieve Data

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function fetch( $id )
    {
        return $this->absorb(
            $this->config_svc->call("variables.types.fetch.id", [
                "id" => $id,
            ])
        )->json();
    }

    // endregion Retrieve Data

    // region Search Data

    /**
     * @param Request $request
     * @return mixed
     */
    public function search( Request $request )
    {
        return $this->absorb(
            $this->config_svc->call("variables.types.search",
                $request->all()
            )
        )->json();
    }

    // endregion Search Data

}
