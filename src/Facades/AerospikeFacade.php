<?php

namespace Lucianojr\Aerospike\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This file is part of Laravel Aerospike,
 * a service for Aerospike Cache.
 *
 * @license MIT
 * @package luciano-jr\laravel-aerospike
 */
class AerospikeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aerospikecache';
    }
}
