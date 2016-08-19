<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aerospike connection
    |--------------------------------------------------------------------------
    |
    | The connection data to connect to Aerospike
    |
    */
    "conn" => [
        "hosts" => [
            ["addr" => "127.0.0.1", "port" => 3000]
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Tokens Set
    |--------------------------------------------------------------------------
    |
    | This is the name of the set to store the facestore tokens
    |
    */
    "tokens_set" => "facestorept_tokens",
];

