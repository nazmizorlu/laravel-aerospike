<?php

namespace Lucianojr\Aerospike;

use Illuminate\Contracts\Logging\Log;
use Lucianojr\Aerospike\Exceptions\CannotConnectionException;
use Aerospike as Client;

class Aerospike
{
    /**
     * The Aerospike client.
     */
    protected $aerospike;

    /**
     * The Aerospike connection that should be used.
     *
     * @var string
     */
    protected $connection;

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
        $this->aerospike = new Client($this->connection, true);
        $this->namespace = $connection['namespace'];

        if (!$this->aerospike->isConnected()) {
            throw new CannotConnectionException();
        }
    }

    /**
     * Get an item from the cache by Set and key.
     *
     * @param $set
     * @param  string|array $key
     * @return mixed
     */
    public function get($set, $key)
    {
        $key_real = $this->initKey($set, $key);
        $result = [];
        $status = $this->aerospike->get($key_real, $result);

        if ($this->isDebug()) {
            switch ($status) {
                case Client::OK:
                    Log::info("The query returned " . count($result) . " records", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
                case Client::ERR_RECORD_NOT_FOUND:
                    Log::info("Could not find");
                    break;
                default:
                    break;
            }
        }

        return $result;
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param string $key
     * @param mixed $value alias "Bins" Aerospike
     * @param int $timeToLive
     * @param array $option
     * @return int status of operation
     */
    public function put($key, $value, $timeToLive = 0, array $option = null)
    {
        if (is_null($option)) {
            $option = [Client::OPT_POLICY_EXISTS => Client::POLICY_EXISTS_CREATE, Client::OPT_POLICY_KEY => Client::POLICY_KEY_SEND];
        }

        $key_real = $this->initKey($key['set'], $key['key']);

        $indexes = [];
        if (isset($value['indexes'])) {
            $indexes = $value['indexes'];
            unset($value['indexes']);
        }

        if (is_array($indexes)) {
            foreach ($indexes as $keyName => $options) {
                $this->addIndex($key['set'], $keyName, $options);
            }
        }

        $status = $this->aerospike->put($key_real, $value, $timeToLive, $option);

        if ($this->isDebug()) {
            switch ($status) {
                case Client::OK:
                    Log::info("Aerospike insert key[{$key_real['key']}] on set[{$key_real['set']}] at namespace[{$key_real['ns']}]", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
                default:
                    Log::error("Aerospike failed create key[{$this->aerospike->errorno()}]: {$this->aerospike->error()}", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
            }
        }

        return $status;
    }

    /**
     * @param $set
     * @param $where
     * @return array
     * @internal param $binField
     * @internal param $token
     */
    public function query($set, $where)
    {
        $result = [];
        $status = $this->aerospike->query($this->namespace, $set, $where, function ($record) use (&$result) {
            $result[] = $record;
        });

        if ($this->isDebug()) {
            switch ($status) {
                case Client::OK:
                    Log::info("The query returned " . count($result) . " records", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
                default:
                    Log::error("An error occured while querying[{$this->aerospike->errorno()}]: {$this->aerospike->error()}", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
            }
        }

        return $result;
    }

    public function scan($key)
    {
        $this->aerospike->scan($this->namespace, $key, function ($record) {
            var_dump($record);
        });
    }

    public function exists($set, $key)
    {
        $key_real = $this->initKey($set, $key);
        $metadata = '';

        $status = $this->aerospike->exists($key_real, $metadata);

        if ($this->isDebug()) {
            Log::info($metadata);
        }

        if ($status == Client::ERR_RECORD_NOT_FOUND) {
            return false;
        }

        if ($status == Client::OK) {
            return true;
        }
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     * @param array $option
     * @return int|void
     */
    public function forever($key, $value, array $option = null)
    {
        return $this->put($key, $value, 0, $option);
    }

    private function initKey($set, $primaryKey, $createDigest = false)
    {
        if ($createDigest) {
            $digest = $this->aerospike->getKeyDigest($this->namespace, $set, $primaryKey);
            return $this->aerospike->initKey($this->namespace, $set, $digest, true);
        }

        return $this->aerospike->initKey($this->namespace, $set, $primaryKey);
    }

    /**
     * Add an index on respective set.
     *
     * @param $set
     * @param $bin
     * @param $index
     */
    private function addIndex($set, $bin, $index)
    {
        $status = $this->aerospike->addIndex($this->namespace, $set, $bin, ...$index);

        if ($this->isDebug()) {
            switch ($status) {
                case Client::OK:
                    Log::info("Index " . $index[0] . " created on " . $this->namespace . ".");
                    break;
                case Client::ERR_INDEX_FOUND:
                    Log::info("Index " . $index[0] . " already created on " . $this->namespace . ".");
                    break;
                default:
                    Log::error("Aerospike failed add Index[{$this->aerospike->errorno()}]: {$this->aerospike->error()}");
                    break;
            }
        }
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     * @return bool
     */
    public function forget($key)
    {
        $key_real = $this->initKey($key['set'], $key['key']);
        $status = $this->aerospike->remove($key_real);

        if ($this->isDebug()) {
            switch ($status) {
                case Client::OK:
                    Log::info("Aerospike removes key[{$key_real['key']}] on set[{$key_real['set']}] at namespace[{$this->namespace}]", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
                default:
                    Log::error("Aerospike failed removing key[{$this->aerospike->errorno()}]: {$this->aerospike->error()}", ['SESSION' => Session::getId(), 'IP' => Request::ip()]);
                    break;
            }
        }

        return $status;
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
     * Get namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set namespace.
     *
     * @param string $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
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
     * @return bool
     */
    private function isDebug()
    {
        return true == Config::get('app.debug');
    }
}