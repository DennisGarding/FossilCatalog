<?php

namespace App\ImportExport;

class MissingArrayKeyException extends \Exception
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('Method fromArray expects to have: "%s" as array key', $key));
    }
}
