<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => '404178339087364',  //client face của bạn
        'client_secret' => '2241116fd4fa663e82d0d063ca2bbf84',  //client app service face của bạn
        'redirect' => 'http://localhost/DATN/Shopthietbi/login-register/callback',
        // 'https://webthietbi.laravel.com/DATN/Shopthietbi/login-register/callback' //callback trả về
    ],
    'google' => [
        'client_id' => '1083870604409-md83ov0negr5b8nej0fhou49jcruq6vv.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost/datn-110120084/Shopthietbi/login-gg/callback'
    ],

];
