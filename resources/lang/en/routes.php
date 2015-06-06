<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Routes Language Lines
    |--------------------------------------------------------------------------
    */

    /** News **/

    'posts.index' => 'news',

    'posts.show' => 'posts/{id?}/{slug?}',

    /** Account **/

    'account.register' => 'register',

    'account.login' => 'auth/login',

    'account.logout' => 'auth/logout',

    /** Shop **/

    'shop.payment.choose-country' => 'shop/payment/choose-country',

    'shop.payment.choose-method' => 'shop/payment/{country?}/choose-method',

    'shop.payment.get-code' => 'shop/payment/get-code',

    'shop.payment.process' => 'shop/payment/process',

    /** Vote **/

    'vote.index' => 'vote',

    'vote.process' => 'vote/process',

    'vote.palier' => 'vote/palier/{id?}',

    'vote.object' => 'vote/object/{item?}',

    /** Events **/

    'event.st-patrick' => 'event/st-patrick',

);
