<?php

namespace Lucianojr\Aerospike\Contracts;

interface ConnectionInterface
{
    /**
     * Opens the connection to Aerospike.
     */
    public function connect();

    /**
     * Closes the connection to Aerospike.
     */
    public function close();

    /**
     * Checks if the connection to Aerospike is considered open.
     *
     * @return bool
     */
    public function isConnected();
}
