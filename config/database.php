<?php

return [
    'host'     => getenv('PRIVACYVISTA_DB_HOST') ?: 'localhost',
    'database' => getenv('PRIVACYVISTA_DB_NAME') ?: 'youwintech_privacyvista_app',
    'username' => getenv('PRIVACYVISTA_DB_USER') ?: 'admin',
    'password' => getenv('PRIVACYVISTA_DB_PASSWORD') ?: 'MySQL@2017',
];
