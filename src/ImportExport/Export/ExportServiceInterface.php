<?php

namespace App\ImportExport\Export;

interface ExportServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function analyzeData(): array;

    /**
     * @return array<string, mixed>
     */
    public function export(): array;

    public function initializeFiles(): void;

    public function clearSession(): void;

    public function createZipFile(string $directory, string $name): string;

    public function deleteBackup(string $path): void;
}