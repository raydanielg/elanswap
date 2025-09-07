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

    // ElanSwap custom services
    'elanswap' => [
        // Default fee amount (TZS). Override via env SERVICES_ELANSWAP_PAYMENT_AMOUNT
        'payment_amount' => env('SERVICES_ELANSWAP_PAYMENT_AMOUNT', 500),
    ],

    // Selcom payments
    'selcom' => [
        // Support both SELCOM_BASE_URL and legacy SELCOM_BASE
        'base_url' => rtrim(env('SELCOM_BASE_URL', env('SELCOM_BASE', 'https://elan.co.tz/api/payments/selcom')), '/') . '/',
        'app_id'   => env('SELCOM_APP_ID', '104'),
        // Optional: timeouts
        'timeout'  => env('SELCOM_TIMEOUT', 15),
        // SSL verification options
        // Set SELCOM_VERIFY=false to skip SSL verification (not recommended for production)
        'verify'   => env('SELCOM_VERIFY', true),
        // If you have a CA bundle path, set SELCOM_CA_PATH and keep verify=true
        'ca_path'  => env('SELCOM_CA_PATH', null),
    ],

    // SMS provider (messaging-service.co.tz)
    'sms' => [
        'base_url' => env('SMS_BASE_URL', 'https://messaging-service.co.tz'),
        'from'     => env('SMS_FROM', 'Elan Brands'),
        'auth'     => env('SMS_AUTH', ''), // e.g., Basic ZWxhbmJyYW5kczpFbGl5YWFtb3MxQA==
        'timeout'  => env('SMS_TIMEOUT', 15),
    ],

];
