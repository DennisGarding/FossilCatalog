<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-zip',
    aliases: ['a:cz'],
    description: 'Creates the relase zip file of the Fossil Catalog application.',
    hidden: false,
)]
class ZipReleaseCommand extends Command
{
    private const BASE_PATH = __DIR__ . '/../../';

    private const BASE_NAME = 'fossil-catalog';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create Zip...');

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::BASE_PATH), \RecursiveIteratorIterator::LEAVES_ONLY);

        $zipFile = new \ZipArchive();
        $zipFile->open(\sprintf('%s/%s.zip', self::BASE_PATH, self::BASE_NAME), \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen(self::BASE_PATH) + 1);

            $zipFile->addFile($filePath, $relativePath);
        }

        $zipFile->close();

        return Command::SUCCESS;
    }
}
