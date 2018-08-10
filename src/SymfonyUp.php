<?php

namespace Netpromotion\SymfonyUp;

use Netpromotion\SymfonyUp\Exception\MismatchException;
use Netpromotion\SymfonyUp\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class SymfonyUp
{
    const ENVIRONMENT = 'APP_ENV';
    const DEBUG = 'APP_DEBUG';

    /**
     * @var callable
     */
    private $kernelFactory;

    private function __construct(callable $kernelFactory)
    {
        $this->kernelFactory = $kernelFactory;
    }

    public static function createFromKernelFactory(callable $kernelFactory): SymfonyUp
    {
        return new static($kernelFactory);
    }

    public static function createFromKernelClass(string $kernelClass): SymfonyUp
    {
        return new static(function ($environment, $debug) use ($kernelClass) {
            return new $kernelClass($environment, $debug);
        });
    }

    public static function createFromKernel(KernelInterface $kernel): SymfonyUp
    {
        return new static(function () use ($kernel) {
            return $kernel;
        });
    }

    public function createKernel(string $environment = 'prod', bool $debug = false): KernelInterface
    {
        /** @var KernelInterface $kernel */
        $kernel = call_user_func($this->kernelFactory, $environment, $debug);

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->checkKernel($kernel, $environment, $debug);

        return $kernel;
    }

    public static function loadEnvironmentIfNeeded(string $pathToEnvFile)
    {
        if (!isset($_SERVER[static::ENVIRONMENT])) {
            if (!class_exists(Dotenv::class)) {
                throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
            }
            (new Dotenv())->load($pathToEnvFile);
        }
    }

    /**
     * @param callable|null $finishRequest Will be called: $finishRequest($request, $response, $kernel)
     */
    public function runWeb(callable $finishRequest = null)
    {
        $environment = $_SERVER[static::ENVIRONMENT] ?? 'dev';
        $debug = (bool) ($_SERVER[static::DEBUG] ?? ('prod' !== $environment));

        $this->handleErrors($debug);

        if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
            Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
        }

        if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
            Request::setTrustedHosts(explode(',', $trustedHosts));
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $kernel = $this->createKernel($environment, $debug);

        if (!$debug) {
            $kernel = new HttpCache($kernel);
        }

        $request = Request::createFromGlobals();
        /** @noinspection PhpUnhandledExceptionInspection */
        $response = $kernel->handle($request);
        if ($finishRequest) {
            $finishRequest($request, $response, $kernel);
        } else {
            $response->send();
            $kernel->terminate($request, $response);
        }
    }

    public function runConsole(InputInterface $input = null, OutputInterface $output = null, $autoExit = true): int
    {
        set_time_limit(0);

        if (null === $input) {
            $input = new ArgvInput();
        }
        $environment = $input->getParameterOption(['--env', '-e'], $_SERVER[static::ENVIRONMENT] ?? 'dev', true);
        $debug = (bool) ($_SERVER[static::DEBUG] ?? ('prod' !== $environment)) && !$input->hasParameterOption('--no-debug', true);

        $this->handleErrors($debug);

        /** @noinspection PhpUnhandledExceptionInspection */
        $kernel = $this->createKernel($environment, $debug);

        $application = new Application($kernel);
        $application->setAutoExit($autoExit);

        /** @noinspection PhpUnhandledExceptionInspection */
        return $application->run($input, $output);
    }

    /**
     * @param KernelInterface $kernel
     * @param string $environment
     * @param bool $debug
     * @throws MismatchException
     */
    private function checkKernel(KernelInterface $kernel, string $environment, bool $debug)
    {
        if ($environment !== $kernel->getEnvironment()) {
            throw new MismatchException('environment', $environment, $kernel->getEnvironment());
        }

        if ($debug !== $kernel->isDebug()) {
            throw new MismatchException('debug', $debug, $kernel->isDebug());
        }
    }

    private function handleErrors(bool $debug)
    {
        if ($debug) {
            umask(0000);

            Debug::enable();
        } else {
            ErrorHandler::register();
            ExceptionHandler::register(false);
        }
    }
}
