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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'netsuite_sb' => [
        'ckey' => env('NETSUITE_CONSUMER_KEY'),
        'csecret' => env('NETSUITE_CONSUMER_SECRET'),
        'tkey' => env('NETSUITE_TOKEN_KEY'),
        'tsecret' => env('NETSUITE_TOKEN_SECRET'),
        'hash' => env('NETSUITE_HASH_TYPE'),
        'realm' => env('NETSUITE_REALM'),
    ],

    'netsuite' => [
        'consumer_key' => env('NETSUITE_CONSUMER_KEY'),
        'consumer_secret' => env('NETSUITE_CONSUMER_SECRET'),
        'token' => env('NETSUITE_TOKEN_KEY'),
        'token_secret' => env('NETSUITE_TOKEN_SECRET'),
        'account_id' => '5184893_SB1',
        'signature_method' => 'HMAC-SHA256',
        'timestamp' => time(),
    ],

];
