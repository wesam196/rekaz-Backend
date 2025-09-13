<?php
return [
    'default' => env('STORAGE_BACKEND', 'db'), // Options: local, db, s3, ftp
    'backends' => [
        'local' => [
            'path' => env('LOCAL_STORAGE_PATH', storage_path('app/blobs')),
        ],
        'db' => [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'table' => 'blobs_data',
        ],
        's3' => [
    'driver' => 's3',
    'key'    => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),        
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
],


        
        'ftp' => [
            'host' => env('FTP_HOST'),
            'port' => env('FTP_PORT', 21),
            'user' => env('FTP_USER'),
            'pass' => env('FTP_PASS'),
            'root' => env('FTP_ROOT', '/'),
            'passive'  => true,
            'ssl'      => false,
            'timeout'  => 30,
        ],
    ],
];
