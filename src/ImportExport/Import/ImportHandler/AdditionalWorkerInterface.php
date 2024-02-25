<?php

namespace App\ImportExport\Import\ImportHandler;

use App\ImportExport\Import\ImportStatus;

interface AdditionalWorkerInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function doWork(array $data, ImportStatus $status): void;
}
