<?php

use Netpromotion\SymfonyUp\SymfonyUp;

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../vendor/autoload.php';

SymfonyUp::loadEnvironmentIfNeeded(__DIR__ . '/.env');

return $loader;
