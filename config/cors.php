<?php
// archivo: config/cors.php

return [
    'paths' => [
        'api/*',
        'listar-imagenes',  // Ruta específica
        'sanctum/csrf-cookie',
        'login',
        'logout'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],  // Permite cualquier origen (solo desarrollo)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],  // Permite todos los headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,  // Cambiar a true si usas cookies/auth
];