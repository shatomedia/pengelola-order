<?php

// This configuration is exclusively for the offline disposable test runner.
$sqlite = getenv('DB_CONNECTION') === 'sqlite' && getenv('DB_DATABASE') === ':memory:';
$disposableMysql = getenv('DB_CONNECTION') === 'mysql'
    && getenv('DB_HOST') === '127.0.0.1'
    && getenv('DB_PORT') === '3306'
    && getenv('DB_DATABASE') === 'sales_disposable_test'
    && getenv('DB_USERNAME') === 'sales_test'
    && getenv('DB_PASSWORD') === 'disposable-test-only';
if (getenv('APP_ENV') !== 'testing' || (!$sqlite && !$disposableMysql)) {
    throw new RuntimeException('Refusing tests without disposable database configuration.');
}
if (file_exists(__DIR__.'/../.env') || file_exists(__DIR__.'/../bootstrap/cache/config.php')) {
    throw new RuntimeException('Refusing tests with a local .env or cached application config.');
}
require __DIR__.'/../vendor/autoload.php';
