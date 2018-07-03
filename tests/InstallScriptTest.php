<?php

namespace Netpromotion\SymfonyUp\Test;

class InstallScriptTest extends \PHPUnit\Framework\TestCase
{
    public function testInstallScriptCreatesAllFiles()
    {
        passthru('cd ' . __DIR__ . '/InstallScript && rm -rf ' . __DIR__ . '/InstallScript/* && echo "yes" | ../../bin/symfony-up');

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
            $this->assertFileExists(__DIR__ . '/InstallScript/' . $expectedFile);
        }
    }
}
