<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\ImportStatus;

interface AdditionalWorkerInterface
{
    public function doWork(array $data, ImportStatus $status): void;
}
