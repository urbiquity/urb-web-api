<?php


namespace Common\Providers;

use Illuminate\Support\ServiceProvider;

class MiddlewareRegistryProvider extends ServiceProvider
{
    public function boot(){

        $files = \base_path('common/Middleware');

        foreach (array_diff(scandir($files), array('.', '..')) as $key => $file) {
            $this->app->make('Illuminate\Contracts\Http\Kernel')
                ->prependMiddleware( get_class_from_file( base_path( 'common/Middleware/' . $file ) ) );
        }
    }
}