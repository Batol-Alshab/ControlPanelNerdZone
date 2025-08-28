<?php
return [


    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // أو ضع هنا الدومينات المسموح بها

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', '*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    'supports_credentials' => false,

];
