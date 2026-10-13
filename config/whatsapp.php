<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Integration Platform URL
    |--------------------------------------------------------------------------
    | The base HTTP API URL of the NestJS WhatsApp Integration Platform.
    */
    'base_url' => env('WHATSAPP_BASE_URL', 'http://localhost:3001/api/v1'),

    /*
    |--------------------------------------------------------------------------
    | Application Credentials
    |--------------------------------------------------------------------------
    | The unique Application ID and secret API Key generated from the
    | WhatsApp Integration Platform dashboard.
    */
    'app_id' => env('WHATSAPP_APP_ID'),
    'api_key' => env('WHATSAPP_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    | Secret key used to verify incoming webhook signatures (X-WA-Signature).
    */
    'webhook_secret' => env('WHATSAPP_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('WHATSAPP_TIMEOUT', 15),

    /*
    |--------------------------------------------------------------------------
    | Default Session ID (Optional)
    |--------------------------------------------------------------------------
    | If your application has multiple sessions, specify the default session ID.
    */
    'default_session_id' => env('WHATSAPP_DEFAULT_SESSION_ID', null),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Management Dashboard (Waku Dashboard)
    |--------------------------------------------------------------------------
    | When enabled, users can visit the management dashboard directly in their
    | browser to connect via QR Code, test sending messages, and view API guides.
    |
    | Default URL: /waku/dashboard (isolated unique route to prevent collision)
    | Middleware: ['web'] (You may add 'auth' to protect it)
    */
    'dashboard' => [
        'enabled' => env('WHATSAPP_DASHBOARD_ENABLED', true),
        'prefix' => env('WHATSAPP_DASHBOARD_PREFIX', 'waku/dashboard'),
        'middleware' => ['web'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Inbound Webhook Route Configuration
    |--------------------------------------------------------------------------
    | Default URL: /waku/api/webhook (isolated unique route to prevent collision)
    */
    'webhook' => [
        'prefix' => env('WHATSAPP_WEBHOOK_PREFIX', 'waku/api/webhook'),
        'middleware' => ['api'],
    ],
];
