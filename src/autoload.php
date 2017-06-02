<?php

if (file_exists(__DIR__ . '/../../../autoload.php')) {
    /** @noinspection PhpIncludeInspection */
    $loader = require __DIR__ . '/../../../autoload.php';
} else {
    /** @noinspection PhpIncludeInspection */
    $loader = require __DIR__ . '/../vendor/autoload.php';
}

if (class_exists(Doctrine\Common\Annotations\AnnotationRegistry::class)) {
    Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([$loader, 'loadClass']);
}

return $loader;
