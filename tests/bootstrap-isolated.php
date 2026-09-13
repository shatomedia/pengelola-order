<?php

// This configuration is exclusively for the offline disposable test runner.
if (getenv('APP_ENV') !== 'testing' || getenv('DB_CONNECTION') !== 'sqlite'
    || getenv('DB_DATABASE') !== ':memory:') {
    throw new RuntimeException('Refusing tests without isolated SQLite in-memory configuration.');
}
if (file_exists(__DIR__.'/../.env') || file_exists(__DIR__.'/../bootstrap/cache/config.php')) {
    throw new RuntimeException('Refusing tests with a local .env or cached application config.');
}
require __DIR__.'/../vendor/autoload.php';
