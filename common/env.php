<?php

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$dotenv->required([
    'DB_DSN',
    'DB_USER',
    'FRONTEND_COOKIE_KEY',
    'BACKEND_COOKIE_KEY',
])->notEmpty();

defined('YII_DEBUG') or define('YII_DEBUG', filter_var($_ENV['YII_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
defined('YII_ENV') or define('YII_ENV', $_ENV['YII_ENV'] ?? 'prod');