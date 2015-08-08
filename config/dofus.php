<?php

return [

    'title' => env('TITLE', 'ServerName'),

    'template' => env('TEMPLATE', 'dofus'),

    'theme' => env('THEME', false),

    'vote' => 10,

    'promos' => [],

    'payment' => [

        'used' => env('PAYMENT', 'dedipass'),

        'starpass' => [
            "name"       => 'Starpass',
            'url'        => 'starpass.json',
            'validation' => 'http://script.starpass.fr/check_php.php?ident={ID}&codes={CODE}DATAS=',
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

    'web-api' => 'http://api.voidmx.net/',

];
