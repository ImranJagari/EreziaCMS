<?php

return [

    'title' => env('TITLE', 'ServerName'),

    'template' => env('TEMPLATE', 'dofus'),

    'theme' => env('THEME', false),

    'vote' => 10,

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
            'id'         => env('PAYMENT_PUBLIC', 0),
            'secret'     => env('PAYMENT_PRIVATE', 0),
        ],

        'dedipass' => [
            "name"       => 'DediPass',
            'url'        => '',
            'validation' => '',
            'id'         => env('PAYMENT_PUBLIC', 0),
            'secret'     => env('PAYMENT_PRIVATE', 0),
        ],

    ],

    'rpg-paradize' => [
        'id'   => env('RPG_PARADIZE', 0),
        'time' => 7205, // 3h + 5s
    ],

    'web-api' => 'http://api.voidmx.net/',

];
