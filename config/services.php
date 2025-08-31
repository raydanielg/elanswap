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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Selcom payment proxy config
    'selcom' => [
        'base'   => env('SELCOM_BASE', 'https://elan.co.tz/api/payments/selcom'),
        'app_id' => env('SELCOM_APP_ID', '104'),
        // SSL options: keep verification ON in production. For local/dev you may set SELCOM_VERIFY=false
        // or provide a custom CA bundle path via SELCOM_CA_PATH to resolve cURL error 60.
        'verify' => env('SELCOM_VERIFY', true),
        'ca_path' => env('SELCOM_CA_PATH'),
    ],

    // SMS provider config
    'sms' => [
        'from' => env('SMS_FROM', 'Elan Brands'),
        'auth' => env('SMS_AUTH'), // base64 encoded user:pass
        'url'  => env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v1/text/single'),
    ],

];
