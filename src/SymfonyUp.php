<?php

namespace Netpromotion\SymfonyUp;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyUp
{
    /**
     * @var callable
     */
    private $kernelFactory;

    private function __construct(callable $kernelFactory)
    {
        $this->kernelFactory = $kernelFactory;
    }

    /**
     * @param callable $kernelFactory
     * @return static
     */
    public static function createFromKernelFactory($kernelFactory)
    {
        return new static($kernelFactory);
    }

    /**
     * @param string $kernelClass
     * @return static
     */
    public static function createFromKernelClass($kernelClass)
    {
        return new static(function ($environment, $debug) use ($kernelClass) {
            return new $kernelClass($environment, $debug);
        });
    }

    /**
     * @param KernelInterface $kernel
     * @return static
     */
    public static function createFromKernel(KernelInterface $kernel)
    {
        return new static(function () use ($kernel) {
            return $kernel;
        });
    }

    /**
     * @param string $environment
     * @param bool $debug
     * @return KernelInterface
     */
    public function createKernel($environment = 'prod', $debug = false)
    {
        /** @var KernelInterface $kernel */
        $kernel = call_user_func($this->kernelFactory, $environment, $debug);
        $this->checkKernel($kernel, $environment, $debug);

        return $kernel;
    }

    /**
     * Prepares and runs web application
     *
     * @param string $environment
     * @param bool $debug
     */
    public function runWeb($environment = 'prod', $debug = false)
    {
        $this->handleErrors($debug);

        $kernel = $this->createKernel($environment, $debug);

        if (!$debug) {
            if (method_exists($kernel, 'loadClassCache')) {
                $kernel->loadClassCache();
            }

            $kernel = new HttpCache($kernel);
        }

        $request = Request::createFromGlobals();
        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);
    }

    /**
     * Prepares and runs console application
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @param bool $autoExit
     * @return int
     * @throws \Exception
     */
    public function runConsole(InputInterface $input = null, OutputInterface $output = null, $autoExit = true)
    {
        set_time_limit(0);

        if (null === $input) {
            $input = new ArgvInput();
        }

        $environment = $input->getParameterOption(['--env', '-e'], getenv('SYMFONY_ENV') ?: 'dev');
        $debug = getenv('SYMFONY_DEBUG') !== '0' && !$input->hasParameterOption(['--no-debug', '']) && $environment !== 'prod';

        $this->handleErrors($debug);

        $kernel = $this->createKernel($environment, $debug);

        $application = new Application($kernel);
        $application->setAutoExit($autoExit);

        return $application->run($input, $output);
    }

    /**
     * @param KernelInterface $kernel
     * @param string $environment
     * @param bool $debug
     */
    private function checkKernel(KernelInterface $kernel, $environment, $debug)
    {
        if ($kernel->getEnvironment() !== $environment) {
            user_error(sprintf(
                'The environment is "%s", expected "%s"',
                $kernel->getEnvironment(),
                $environment
            ), E_USER_ERROR);
        }

        if ($kernel->isDebug() !== $debug) {
            user_error(sprintf(
                'The debug is %s, expected %s',
                var_export($kernel->isDebug(), true),
                var_export($debug, true)
            ), E_USER_WARNING);
        }
    }

    private function handleErrors($debug)
    {
        if ($debug) {
            Debug::enable();
        } else {
            ErrorHandler::register();
            ExceptionHandler::register(false);
        }
    }
}
