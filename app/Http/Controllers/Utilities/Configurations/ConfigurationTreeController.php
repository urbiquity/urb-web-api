<?php

namespace App\Http\Controllers\Utilities\Configurations;

use Common\Microservices\Utilities\Configurations\ConfigurationMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class ConfigurationTreeController
 * @package App\Http\Controllers\Utilities\Configurationss
 */
class ConfigurationTreeController extends BaseController
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

    public function get( Request $request, $target_id )
    {
        return $this->absorb(
            $this->config_svc->call( 'configurations.target_id.tree.get' , [
                'target_id' => $target_id,
            ])
        )->json();
    }

    public function clean( Request $request, $target_id )
    {
        return $this->absorb(
            $this->config_svc->call( 'configurations.target_id.tree.get.clean' , [
                'target_id' => $target_id,
            ])
        )->json();
    }

}
