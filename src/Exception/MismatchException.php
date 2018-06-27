<?php

namespace Netpromotion\SymfonyUp\Exception;

class MismatchException extends SymfonyUpException
{
    public function __construct(string $subject, $expected, $actual)
    {
        $this->message = sprintf(
            'The %s is %s, expected %s',
            $subject,
            var_export($actual, true),
            var_export($expected, true)
        );
    }
}
