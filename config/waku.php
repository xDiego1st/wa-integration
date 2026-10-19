<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Integration Platform Gateway URL
    |--------------------------------------------------------------------------
    | The base HTTP API URL of the NestJS WhatsApp Integration Platform.
    */
    'base_url' => env('WAKU_BASE_URL', env('WHATSAPP_BASE_URL', 'http://localhost:3001/api/v1')),

    /*
    |--------------------------------------------------------------------------
    | Application Credentials
    |--------------------------------------------------------------------------
    | The unique Application ID and secret API Key generated from the
    | WhatsApp Integration Platform dashboard.
    */
    'app_id' => env('WAKU_APP_ID', env('WHATSAPP_APP_ID')),
    'api_key' => env('WAKU_API_KEY', env('WHATSAPP_API_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    | Secret key used to verify incoming webhook signatures (X-WA-Signature).
    */
    'webhook_secret' => env('WAKU_WEBHOOK_SECRET', env('WHATSAPP_WEBHOOK_SECRET')),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('WAKU_TIMEOUT', env('WHATSAPP_TIMEOUT', 15)),

    /*
    |--------------------------------------------------------------------------
    | Default Session ID & Bot Key (Optional)
    |--------------------------------------------------------------------------
    | If your application has multiple sessions or bots, specify the default identifiers.
    */
    'default_session_id' => env('WAKU_DEFAULT_SESSION_ID', env('WHATSAPP_DEFAULT_SESSION_ID', null)),
    'default_bot_key' => env('WAKU_DEFAULT_BOT_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Waku Management Dashboard (/waku/dashboard)
    |--------------------------------------------------------------------------
    | Panel visual manajemen WhatsApp mandiri untuk scanning Baileys QR Code,
    | pengujian kirim pesan real-time, inspeksi log, dan petunjuk API.
    |
    | - enabled: Aktifkan / nonaktifkan dashboard secara global.
    | - prefix: URL prefix (default: 'waku/dashboard').
    | - middleware: Pipeline middleware Laravel. Wajib 'auth' secara default.
    | - auth_required: Wajib login untuk membuka dashboard.
    | - allowed_roles: Array role yang diizinkan mengakses dashboard.
    |   Mendukung Spatie Permission (hasRole), Laratrust, atau field role model User.
    |   Contoh: ['SUPER-ADMIN', 'ADMIN']
    |   Biarkan kosong [] jika semua user terautentikasi diizinkan mengakses.
    | - allowed_emails: Array email user spesifik yang diizinkan.
    |   Contoh: ['admin@pekanbaru.go.id', 'developer@domain.com']
    | - gate: Nama Laravel Gate / Policy (opsional).
    */
    'dashboard' => [
        'enabled' => env('WAKU_DASHBOARD_ENABLED', env('WHATSAPP_DASHBOARD_ENABLED', true)),
        'prefix' => env('WAKU_DASHBOARD_PREFIX', env('WHATSAPP_DASHBOARD_PREFIX', 'waku/dashboard')),
        'middleware' => ['web', 'auth'],

        // Wajibkan autentikasi login pengguna
        'auth_required' => env('WAKU_AUTH_REQUIRED', true),

        // Batasi akses berdasarkan Role pengguna
        // Contoh: ['SUPER-ADMIN', 'ADMIN']
        'allowed_roles' => [
            // 'SUPER-ADMIN',
            // 'ADMIN',
        ],

        // Batasi akses berdasarkan Email pengguna tertentu
        // Contoh: ['admin@pekanbaru.go.id']
        'allowed_emails' => [
            // 'admin@pekanbaru.go.id',
        ],

        // Custom Laravel Gate (opsional)
        'gate' => null,

        // Tujuan pengalihan URL jika pengguna tidak memiliki hak akses role/email
        'redirect_unauthorized' => env('WAKU_REDIRECT_UNAUTHORIZED', '/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Inbound Webhook Route Configuration
    |--------------------------------------------------------------------------
    | Default URL: /waku/api/webhook (isolated unique route to prevent collision)
    */
    'webhook' => [
        'prefix' => env('WAKU_WEBHOOK_PREFIX', env('WHATSAPP_WEBHOOK_PREFIX', 'waku/api/webhook')),
        'middleware' => ['api'],
    ],
];
