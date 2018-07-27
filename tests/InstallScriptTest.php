<?php

namespace Netpromotion\SymfonyUp\Test;

class InstallScriptTest extends \PHPUnit\Framework\TestCase
{
    const NEW_APP_DIR = __DIR__ . '/../sandbox/NewApp';

    public function testInstallScriptCreatesAllFiles()
    {
        passthru('cd ' . static::NEW_APP_DIR . ' && rm -rf ' . static::NEW_APP_DIR . '/* && echo "yes" | ../../bin/symfony-up');

        $expectedFiles = [
            '.env.dist',
            'bin/console',
            'config/bundles.php',
            'config/packages/framework.yaml',
            'config/routes/annotations.yaml',
            'phpunit.xml',
            'public/.htaccess',
            'public/index.php',
            'src/Kernel.php',
            'tests/bootstrap.php',
            'tests/TestCase.php',
        ];
        foreach ($expectedFiles as $expectedFile) {
            $this->assertFileExists(static::NEW_APP_DIR . '/' . $expectedFile);
        }
    }
}
