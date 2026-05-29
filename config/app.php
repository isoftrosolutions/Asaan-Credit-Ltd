<?php
return [
    'name' => 'InvestMatch Nepal',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => (getenv('APP_DEBUG') ?: false) === true,
    'url' => getenv('APP_URL') ?: 'http://invest-match.test',
    'storage_path' => __DIR__ . '/../storage',
];
