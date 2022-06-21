<?php namespace bpmason\threadedComments;

/**
 * Part of the ThreadedComments package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the MIT License.
 *
 * This source file is subject to the MIT License that is
 * bundled with this package in the LICENSE file.
 * It is also available at the following URL: http://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 * @author     BMason
 * @license    MIT
 */



class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }
}
