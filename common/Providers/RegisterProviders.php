<?php

namespace Common\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * File loader for global functions.
 * This avoids updating composer.json when a new file is needed
 *
 * Class HelperServiceProvider
 * @package App\Providers
 */
class RegisterProviders extends ServiceProvider
{
    protected $classes = [
        \Common\Providers\HelperServiceProvider::class,
        \Common\Providers\MiddlewareRegistryProvider::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach( $this->classes as $class ){
            $this->app->register( $class );
        }

        if( config("common.providers") !== null ){
            foreach( (array) config("common.providers") as $provider ){
                $this->app->register( $provider );
            }
        }
    }

    /**
     * Register helpers from common/Helpers directory.
     *
     * @return void
     */
    public function register()
    {
    }
}
