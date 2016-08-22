<?php

namespace LaravelAerospike\Contracts;


interface ClientInterface
{
    public function addIndex($namespace, $key, $value);
    public function errorno();
}