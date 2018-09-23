<?php

namespace Netpromotion\SymfonyUp\Console\Message;

use Symfony\Component\HttpKernel\KernelInterface;

abstract class AddEnvironment
{
    public static function colored(KernelInterface $kernel, string $message): string
    {
        return sprintf($message, sprintf(
            'for the <info>%s</info> environment with debug <info>%s</info>',
            $kernel->getEnvironment(),
            var_export($kernel->isDebug(), true)
        ));
    }

    public static function plain(KernelInterface $kernel, string $message): string
    {
        return sprintf($message, sprintf(
            'for the "%s" environment (debug=%s)',
            $kernel->getEnvironment(),
            var_export($kernel->isDebug(), true)
        ));
    }
}
