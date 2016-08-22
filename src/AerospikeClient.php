<?php

namespace LaravelAerospike;

use Aerospike as Client;
use LaravelAerospike\Contracts\ClientInterface;
use LaravelAerospike\Contracts\ConnectionInterface;

class AerospikeClient extends Client implements ClientInterface, ConnectionInterface
{
    /**
     * Closes the connection to Aerospike.
     */
    public function close()
    {
        parent::close();
    }

    function addIndex($namespace, $key, $value)
    {
        parent::addIndex($namespace, $key, $value);
    }

    function errorno()
    {
        parent::errorno();
    }

    /**
     * Opens the connection to Aerospike.
     */
    public function connect()
    {
        parent::connect();
    }

    /**
     * Checks if the connection to Aerospike is considered open.
     *
     * @return bool
     */
    public function isConnected()
    {
        parent::isConnected();
    }
}