<?php

namespace Lucianojr\Aerospike\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Support\ServiceProvider;
use Lucianojr\Aerospike\Aerospike as Store;

class AerospikeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     */
    public function register()
    {
        $this->app->singleton('aerospikecache', function () {

            $connection = $this->getAerospikeConnection();

            return new Store(
                $connection,
                $this->app->make(Log::class),
                $this->app->make(Session::class),
                $this->app->make(Request::class),
                $this->app->make(Repository::class)
            );
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/aerospike.php' => config_path('aerospike.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['aerospikecache'];
    }

    /**
     * Get the database connection for the MongoDB driver.
     *
     * @throws \InvalidArgumentException
     */
    protected function getAerospikeConnection()
    {
        return $this->app['config']['aerospike'];
    }
}