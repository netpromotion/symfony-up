<?php

namespace Netpromotion\SymfonyUp\Test;

use Netpromotion\SymfonyUp\SymfonyUp;
use Netpromotion\SymfonyUp\Test\SomeApp\SomeKernel;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @runTestsInSeparateProcesses otherwise the error handler will be changed
 */
class SymfonyUpTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataCreateFromWorks
     * @param string $method
     * @param mixed $input
     */
    public function testCreateFromWorks($method, $input)
    {
        $this->assertInstanceOf(SymfonyUp::class, SymfonyUp::{$method}($input));
    }

    public function dataCreateFromWorks()
    {
        return [
            'kernel factory' => ['createFromKernelFactory', [$this, __FUNCTION__]],
            'kernel class' => ['createFromKernelClass', __CLASS__],
            'kernel' => ['createFromKernel', new SomeKernel('dev', true)],
        ];
    }

    /**
     * @dataProvider dataKernelFactoryGetsEnvironmentAndDebug
     * @param string $environment
     * @param bool $debug
     * @throws \Exception
     */
    public function testKernelFactoryGetsEnvironmentAndDebug($environment, $debug)
    {
        $factoryCalled = false;
        try {
            $_SERVER[SymfonyUp::ENVIRONMENT] = $environment;
            $_SERVER[SymfonyUp::DEBUG] = $debug;

            SymfonyUp::createFromKernelFactory(function ($a, $b) use ($environment, $debug, &$factoryCalled) {
                $this->assertEquals($environment, $a);
                $this->assertEquals($debug, $b);

                $factoryCalled = true;

                return new SomeKernel($environment, $debug);
            })->runWeb();
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
     * @dataProvider dataLoadsEnvironmentIfNeeded
     * @param string|null $preset
     * @param string $expected
     */
    public function testLoadsEnvironmentIfNeeded($preset, $expected)
    {
        if ($preset) {
            $_SERVER[SymfonyUp::ENVIRONMENT] = 'test';
            $_SERVER['SYMFONY_UP'] = $preset;
        } else {
            unset($_SERVER[SymfonyUp::ENVIRONMENT]);
        }

        SymfonyUp::createFromKernel(new SomeKernel('test', true))
            ->loadEnvironmentIfNeeded(__DIR__ . '/.env');

        $this->assertSame($expected, $_SERVER['SYMFONY_UP']);
    }

    public function dataLoadsEnvironmentIfNeeded()
    {
        return [
            [null, 'From .env file'],
            ['From $_SERVER', 'From $_SERVER'],
        ];
    }

    /**
     * @dataProvider dataRunWebWorks
     * @param string $environment
     * @param bool $debug
     * @throws \Exception
     */
    public function testRunWebWorks($environment, $debug)
    {
        if (!$debug) {
            $this->expectException(NotFoundHttpException::class); // There is no route for /
        }

        $_SERVER[SymfonyUp::ENVIRONMENT] = $environment;
        $_SERVER[SymfonyUp::DEBUG] = $debug;

        SymfonyUp::createFromKernelClass(SomeKernel::class)->runWeb();

        if ($debug) {
            $this->assertSame(Response::HTTP_NOT_FOUND, http_response_code()); // There is no route for /
        }
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
     * @throws \Exception
     */
    public function testRunConsoleWorks($environment, $debug)
    {
        $input = new ArrayInput([
            'command' => 'about',
            '--env' => $environment,
            '--' . ($debug ? '' : 'no-') . 'debug',
        ]);

        $output = new BufferedOutput();

        SymfonyUp::createFromKernelClass(SomeKernel::class)->runConsole($input, $output, false);

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
     * @param string|int $expectedExceptionOrStatusCode
     * @throws \Exception
     */
    public function testCheckKernelWorks($kernel, $environment, $debug, $expectedExceptionOrStatusCode)
    {
        try {
            $_SERVER[SymfonyUp::ENVIRONMENT] = $environment;
            $_SERVER[SymfonyUp::DEBUG] = $debug;

            SymfonyUp::createFromKernel($kernel)->runWeb();

            if (is_int($expectedExceptionOrStatusCode)) {
                $this->assertSame($expectedExceptionOrStatusCode, http_response_code());
            } else {
                $this->fail('Exception was expected');
            }
        } catch (\Exception $e) {
            if (is_int($expectedExceptionOrStatusCode)) {
                throw $e;
            } else {
                $this->assertContains($expectedExceptionOrStatusCode, $e->getMessage());
            }
        }
    }

    public function dataCheckKernelWorks()
    {
        return [
            [new SomeKernel('dev', true), 'dev', true, Response::HTTP_NOT_FOUND],
            [new SomeKernel('dev', true), 'dev', false, 'The debug is true, expected false'],
            [new SomeKernel('dev', true), 'prod', true, 'The environment is ' . var_export("dev", true) . ', expected ' . var_export("prod", true) . ''],
            [new SomeKernel('dev', true), 'prod', false, 'The environment is ' . var_export("dev", true) . ', expected ' . var_export("prod", true) . ''],
        ];
    }
}
