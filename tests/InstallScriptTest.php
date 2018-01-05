<?php

namespace Netpromotion\SymfonyUp\Test;

class InstallScriptTest extends \PHPUnit_Framework_TestCase
{
    public function testInstallScriptCreatesAllFiles()
    {
        passthru('cd ' . __DIR__ . '/InstallScript && rm -rf ' . __DIR__ . '/InstallScript/* && echo "yes" | ../../bin/symfony-up');

        $expectedFiles = [
            'app/config/parameters.yml',
            'app/config/config.yml',
            'app/config/config_dev.yml',
            'app/autoload.php',
            'app/AppKernel.php',
            'tests/AppTestCase.php',
            'web/app.php',
            'web/app_dev.php',
            'web/.htaccess',
            'bin/console',
            'phpunit.xml'
        ];
        foreach ($expectedFiles as $expectedFile) {
            $this->assertFileExists(__DIR__ . '/InstallScript/' . $expectedFile);
        }
    }
}
