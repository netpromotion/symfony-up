<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\SymfonyUp;
use Netpromotion\SymfonyUp\Test\SomeApp\SomeKernel;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @runTestsInSeparateProcesses otherwise the error handler will be changed
 */
class SymfonyUpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataViaWorks
     * @param string $method
     * @param mixed $input
     */
    public function testViaWorks($method, $input)
    {
        SymfonyUp::{$method}($input);
    }

    public function dataViaWorks()
    {
        return [
            ['viaKernelFactory', [$this, __FUNCTION__]],
            ['viaKernelClass', __CLASS__],
            ['viaKernel', new SomeKernel('dev', true)]
        ];
    }

    /**
     * @dataProvider dataKernelFactoryGetsEnvironmentAndDebug
     * @param string $environment
     * @param bool $debug
     */
    public function testKernelFactoryGetsEnvironmentAndDebug($environment, $debug)
    {
        $factoryCalled = false;
        try {
            SymfonyUp::viaKernelFactory(function ($a, $b) use ($environment, $debug, &$factoryCalled) {
                $this->assertEquals($environment, $a);
                $this->assertEquals($debug, $b);

                $factoryCalled = true;

                return new SomeKernel($environment, $debug);
            })->runWeb($environment, $debug);
        } catch (NotFoundHttpException $ignored) {
            // There is no route for /
        }
        $this->assertTrue($factoryCalled);
    }

    public function dataKernelFactoryGetsEnvironmentAndDebug()
    {
        return [
            ['dev', true],
            ['dev', false],
            ['prod', false],
        ];
    }

    /**
     * @dataProvider dataRunWebWorks
     * @param string $environment
     * @param bool $debug
     */
    public function testRunWebWorks($environment, $debug)
    {
        $this->setExpectedException(NotFoundHttpException::class); // There is no route for /

        SymfonyUp::viaKernelClass(SomeKernel::class)->runWeb($environment, $debug);
    }

    public function dataRunWebWorks()
    {
        return [
            ['dev', true],
            ['dev', false],
            ['prod', false],
        ];
    }

    /**
     * @dataProvider dataRunConsoleWorks
     * @param string $environment
     * @param bool $debug
     */
    public function testRunConsoleWorks($environment, $debug)
    {
        $input = new ArrayInput([
            'command' => 'about',
            '--env' => $environment,
            '--' . ($debug ? '' : 'no-') . 'debug',
        ]);

        $output = new BufferedOutput();

        SymfonyUp::viaKernelClass(SomeKernel::class)->runConsole($input, $output, false);

        $this->assertStringMatchesFormat(
            '%aEnvironment%w' . $environment . '%aDebug%w' . var_export($debug, true) . '%a',
            $output->fetch()
        );
    }

    public function dataRunConsoleWorks()
    {
        return [
            ['dev', true],
            ['dev', false],
            ['prod', false],
        ];
    }

    /**
     * @dataProvider dataCheckKernelWorks
     * @param KernelInterface $kernel
     * @param string $environment
     * @param bool $debug
     * @param string $expectedException
     */
    public function testCheckKernelWorks($kernel, $environment, $debug, $expectedException)
    {
        try {
            SymfonyUp::viaKernel($kernel)->runWeb($environment, $debug);

            $this->fail('Exception was expected');
        } catch (\Exception $e) {
            $this->assertContains($expectedException, $e->getMessage());
        }
    }

    public function dataCheckKernelWorks()
    {
        return [
            [new SomeKernel('dev', true), 'dev', true, 'No route found for "GET /"'],
            [new SomeKernel('dev', true), 'dev', false, 'The debug is true, expected false'],
            [new SomeKernel('dev', true), 'prod', true, 'The environment is "dev", expected "prod"'],
            [new SomeKernel('dev', true), 'prod', false, 'The environment is "dev", expected "prod"'],
        ];
    }
}
