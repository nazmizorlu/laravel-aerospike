<?php

namespace LaravelAerospike;

use LaravelAerospike\Repositories\AerospikeStore;
use Illuminate\Support\ServiceProvider;

class AerospikeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::extend('aerospike', function($app) {
            return Cache::repository(new AerospikeStore($app['config']['database.aerospike']));
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['aerospike'];
    }

}