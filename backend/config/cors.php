<?php

use Illuminate\Support\Facades\Request;

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'auth/google', 'auth/google/callback', '/api/me'],
    
    'allowed_methods' => ['*'],
    
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://localhost:5173',
        'http://127.0.0.1:5173'
    ],
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => ['*'],
    
    'exposed_headers' => [],
    
    'max_age' => 0,
    
    'supports_credentials' => true,
];