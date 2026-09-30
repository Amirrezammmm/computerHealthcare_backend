<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // مسیرهایی که فرانت باید بهشون دسترسی CORS داشته باشه
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
    ],

    // متدهای مجاز (GET, POST, PUT, DELETE و ...)
    'allowed_methods' => ['*'],

    // آدرس دقیق فرانت‌اند (Next.js) - نباید ستاره '*' باشه!
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // بسیار حیاتی: اجازه ارسال کوکی‌ها و سشن به فرانت‌اند
    'supports_credentials' => true,

];
