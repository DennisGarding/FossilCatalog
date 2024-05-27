<?php

namespace App\Update\Exceptions;

class VerifyFileException extends \Exception
{
    public function __construct(string $version, string $expectedSha, string $actualSha)
    {
        parent::__construct(
            \sprintf(
                'Failed to verify the update file with version %s. Expected SHA: %s, Actual SHA: %s',
                $version,
                $expectedSha,
                $actualSha
            )
        );
    }
}
