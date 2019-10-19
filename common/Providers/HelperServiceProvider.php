<?php

namespace Common\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * File loader for global functions.
 * This avoids updating composer.json when a new file is needed
 *
 * Class HelperServiceProvider
 * @package App\Providers
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register helpers from common/Helpers directory.
     *
     * @return void
     */
    public function register()
    {
        //
        $files = \base_path('common/Helpers');

        foreach (array_diff(scandir($files), array('.', '..')) as $key => $file) {
            require_once base_path('common/Helpers/' . $file);
        }
    }
}
