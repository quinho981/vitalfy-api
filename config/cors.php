<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // TODO: Em produção, substitua pelo domínio real do frontend via FRONTEND_URL.
    // Nunca use '*' com credentials=true — os browsers rejeitam.
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Necessário para cookies HttpOnly funcionarem em requisições cross-origin
    'supports_credentials' => true,

];
