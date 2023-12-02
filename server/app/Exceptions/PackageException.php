<?php

namespace App\Exceptions;

final class PackageException extends \Exception
{
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }

    public static function alreadyBeingTracked(string $trackingCode): PackageException
    {
        return new PackageException(
            sprintf('The package with tracking code: %s is already being tracked by the user', $trackingCode)
        );
    }
}
