<?php

namespace LaravelAerospike\Repositories;

use Aerospike as AerospikeClient;
use LaravelAerospike\Exceptions\CannotConnectionException;
use Illuminate\Contracts\Cache\Store;
use LogicException;

class AerospikeStore implements Store
{
    /**
     * The Aerospike database connection.
     *
     * @var AerospikeClient
     */
    protected $aerospike;

    /**
     * The Aerospike connection that should be used.
     *
     * @var string
     */
    protected $connection;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * A namespace that should be used.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new Aerospike store.
     *
     * @param string $connection
     * @throws CannotConnectionException
     */
    public function __construct($connection)
    {
        $this->connection = $connection['conn'];
        $this->aerospike = new AerospikeClient($this->connection, true);
        $this->namespace = $connection['namespace'];

        if (! $this->aerospike->isConnected())
        {
            throw new CannotConnectionException();
        }
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string|array $key
     * @return mixed
     */
    public function get($key)
    {
        $status = $this->aerospike->exists($key, $metadata);
        if ($status == AerospikeClient::OK) {
            var_dump($metadata);
        }
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array $keys
     * @return array
     */
    public function many(array $keys)
    {
        // TODO: Implement many() method.
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  float|int $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        $key = $this->aerospike->initKey("test", "characters", 3);
        $bins = ["name" => "Bender", "Occupation" => "Bender", "age" => 1055];
        // store the key data with the record, rather than just its digest
        $option = [AerospikeClient::OPT_POLICY_KEY => AerospikeClient::POLICY_KEY_SEND];
        $a = $this->aerospike->put($key, $bins, 0, $option);
        echo $a.'asdasd';
//        $this->aerospike->addIndex($this->namespace, $key, $value);
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * @param  array $values
     * @param  float|int $minutes
     * @return void
     */
    public function putMany(array $values, $minutes)
    {
        // TODO: Implement putMany() method.
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        throw new LogicException("Not supported by this driver.");
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        throw new LogicException("Not supported by this driver.");
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function forever($key, $value)
    {
        // TODO: Implement forever() method.
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function forget($key)
    {
        // TODO: Implement forget() method.
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        // TODO: Implement flush() method.
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


    /**
     * Set the cache key prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = ! empty($prefix) ? $prefix.':' : '';
    }
}