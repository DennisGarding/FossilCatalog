<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ExportRepository
{
    private string $exportDirectory;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $rootDirectory,
    ) {
        $this->exportDirectory = sprintf(
            '%s/%s',
            $this->rootDirectory,
            'public/export',
        );
    }

    public function getExports(): array
    {
        $finder = new Finder();

        $finder->directories()->in($this->exportDirectory);

        if (!$finder->hasResults()) {
            return [];
        }

        $finder->sortByName()->reverseSorting();

        $exportArray = [];
        foreach ($finder as $directory) {
            $exportArray[] = [
                'name' => $directory->getRelativePathname(),
                'realPath'=> $directory->getRealPath(),
                'hasFinished' => !file_exists($directory->getRealPath() . '/in_progress.lock')
            ];
        }

        return $exportArray;
    }

    public function delete(string $path): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($path);
    }
}