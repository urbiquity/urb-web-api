<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapDefaultRoutes();

        $this->mapMicroserviceRoutes();

        // $this->mapApiRoutes();

        // $this->mapWebRoutes();
    }

    protected function mapDefaultRoutes(){
        Route::namespace("\Common\Controllers")
            ->group( $this->routePath( "defaults" ) );
    }

    protected function mapMicroserviceRoutes(){
        Route::namespace($this->namespace)
            ->prefix('api')
            ->group($this->routePath( "api" ) );
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    private function routePath( $file ){
        if( !Str::contains( $file, ".php") ){
            $file .= ".php";
        }

        return base_path('routes/' . $file);
    }
}
