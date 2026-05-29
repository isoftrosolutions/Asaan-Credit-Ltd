<?php
define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/../bootstrap/app.php';

\App\Core\Auth::startSession();
clear_old();

if (in_array(\App\Core\Request::method(), ['POST', 'PUT', 'DELETE'])) {
    verify_csrf();
}

\App\Core\Router::dispatch();
