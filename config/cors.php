<?php

return [
    /*
     * Rutas de Laravel que aceptan peticiones CORS.
     * El * incluye todas las rutas de la API.
     */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
     * Métodos HTTP permitidos desde el frontend.
     */
    'allowed_methods' => ['*'],

    /*
     * Orígenes permitidos — dominios que pueden hacer peticiones.
     * En desarrollo: localhost:3000 (Next.js)
     */
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    'allowed_origins_patterns' => [],

    /*
     * Headers permitidos en las peticiones.
     */
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * Permite enviar cookies en peticiones cross-origin.
     */
    'supports_credentials' => false,
];