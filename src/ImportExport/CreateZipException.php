<?php

namespace App\ImportExport;

class CreateZipException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Error while creating zip file.');
    }
}