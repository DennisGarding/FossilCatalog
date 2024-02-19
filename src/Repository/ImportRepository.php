<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ImportRepository
{
    private string $importDirectory;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly string $rootDirectory,
    ) {
        $this->importDirectory = sprintf(
            '%s/%s',
            $this->rootDirectory,
            'public/import',
        );
    }

    public function getList(): array
    {
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($this->importDirectory);

        if (!$finder->hasResults()) {
            return [];
        }

        $finder->sortByName()->reverseSorting();

        $importsArray = [];
        foreach ($finder as $directory) {
            $importsArray[] = [
                'name' => $directory->getRelativePathname(),
                'realPath'=> $directory->getRealPath(),
            ];
        }

        return $importsArray;
    }

    public function delete(string $path): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($path);
    }
}
