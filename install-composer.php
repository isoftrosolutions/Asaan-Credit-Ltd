<?php
echo "Downloading Composer...\n";
copy('https://getcomposer.org/installer', 'composer-setup.php');

echo "Verifying installer...\n";
$expected = trim(file_get_contents('https://composer.github.io/installer.sig'));
$actual = hash_file('sha384', 'composer-setup.php');

if ($expected !== $actual) {
    echo "ERROR: Invalid installer signature\n";
    unlink('composer-setup.php');
    exit(1);
}

echo "Installing Composer...\n";
require 'composer-setup.php';

if (file_exists('composer.phar')) {
    echo "\nComposer installed successfully!\n";
    echo "Install dependencies: php composer.phar install --no-dev --optimize-autoloader\n";
}

unlink('composer-setup.php');
