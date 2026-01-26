<?php
declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => false, // set TRUE if HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
