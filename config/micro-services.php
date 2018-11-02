<?php
/**
 * User: qbhy
 * Date: 2018/10/23
 * Time: 下午4:50
 */

return [
    // 当前使用哪个 APP
    'use'        => 'default',

    // 前端用于区分APP的请求头
    'app_header' => 'App',

    // 微服务base uri
    'base_uri'   => env('MICRO_SERVICE_BASE_URI'),

    // APP列表
    'apps'       => [
        'default' => [
            'id'       => env('MICRO_SERVICE_APP_ID'),
            'secret'   => env('MICRO_SERVICE_APP_SECRET'),
            'token'    => env('MICRO_SERVICE_APP_TOKEN'),
            'handlers' => [
                'payment' => null,
            ]
        ]
    ],
];