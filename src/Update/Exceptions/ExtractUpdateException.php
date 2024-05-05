<?php

namespace App\Update\Exceptions;

class ExtractUpdateException extends \Exception
{
    public function __construct(string $version)
    {
        parent::__construct(\sprintf('Failed to extract the update file with version %s', $version));
    }
}
