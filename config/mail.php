<?php
return [
    'mailer' => getenv('MAIL_MAILER') ?: 'smtp',
    'host' => getenv('MAIL_HOST') ?: 'mailpit',
    'port' => getenv('MAIL_PORT') ?: 1025,
    'username' => getenv('MAIL_USERNAME'),
    'password' => getenv('MAIL_PASSWORD'),
    'encryption' => getenv('MAIL_ENCRYPTION'),
    'from_address' => getenv('MAIL_FROM_ADDRESS') ?: 'noreply@investmatch.com',
    'from_name' => getenv('MAIL_FROM_NAME') ?: 'InvestMatch Nepal',
];
