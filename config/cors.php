<?php

return [
    'paths' => ['api/*', 'oauth/token'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_filter(explode(',', env('API_ALLOWED_ORIGINS', 'http://localhost,http://127.0.0.1'))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
