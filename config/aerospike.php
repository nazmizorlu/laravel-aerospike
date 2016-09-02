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
    'conn' => [
        'hosts' => [
            [
                "addr" => env('AEROSPIKE_HOST', 'localhost'),
                "port" => env('AEROSPIKE_PORT', 3000)
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespace
    |--------------------------------------------------------------------------
    |
    | This is the default namespace to store.
    |
    */
    'namespace' => env('AEROSPIKE_NAMESPACE', 'test'),

];
