<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午4:50
 */

return [
    'use'        => 'default',
    'app_header' => 'App',
    'base_uri'   => env('MICRO_SERVICE_BASE_URI'),
    'apps'       => [
        'default' => [
            'id'     => env('MICRO_SERVICE_APP_ID'),
            'secret' => env('MICRO_SERVICE_APP_SECRET'),
            'token'  => env('MICRO_SERVICE_APP_TOKEN'),
        ]
    ],
];