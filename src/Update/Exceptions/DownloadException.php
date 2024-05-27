<?php

namespace App\Update\Exceptions;

class DownloadException extends \Exception
{
    private const MESSAGE = 'Failed to download the update file with version %s';

    public function __construct(string $version)
    {
        parent::__construct(\sprintf(self::MESSAGE, $version));
    }
}
