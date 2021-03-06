<?php

return [

    'title' => env('TITLE', 'ServerName'),

    'subtitle' => env('SUBTITLE', 'SubTitle'),

    'template' => env('TEMPLATE', 'dofus'),

    'theme' => env('THEME', false),

    'carousel' => env('CAROUSEL', false),

    'vote' => 10,

    'promos' => [],

    'payment' => [

        'used' => env('PAYMENT', 'dedipass'),

        'starpass' => [
            "name"       => 'Starpass',
            'url'        => 'starpass.json',
            'validation' => 'http://script.starpass.fr/check_php.php?ident={KEY}&codes={CODE}DATAS=',
            'idp'        => env('PAYMENT_PUBLIC', 0),
            'idd'        => env('PAYMENT_PRIVATE', 0),
        ],

        'oneopay' => [
            "name"       => 'OneoPay',
            'url'        => 'http://oneopay.com/api/rates.php?service=15',
            'validation' => 'https://oneopay.com/api/checkcode.php?service=1&rate={PALIER}&code={CODE}',
            'key'        => env('PAYMENT_PUBLIC', 0),
        ],

        'dedipass' => [
            "name"       => 'DediPass',
            'url'        => 'https://buy.buycode.eu/v1/pay/rates?key={KEY}',
            'validation' => 'http://api.dedipass.com/v1/pay/?key={KEY}&rate={PALIER}&code={CODE}',
            'key'        => env('PAYMENT_PUBLIC', 0),
        ],

    ],

    'rpg-paradize' => [
        'id'   => env('RPG_PARADIZE', 0),
        'delay' => 10810, // 3h + 5s
    ],

    'web-api' => 'http://127.0.0.1/api/',

    'shop' => [
        'host' => gethostbyname('voidmx.net'),
        'port' => 7002,
    ],

];
