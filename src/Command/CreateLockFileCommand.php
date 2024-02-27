<?php

namespace App\Command;

use App\Static\Installation\LockFile;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-lock-file',
    aliases: ['a:clf'],
    description: 'Creates the lock file for the Fossil Catalog application.',
    hidden: false,
)]
class CreateLockFileCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create lock file...');
        LockFile::createInstallationLockFile();

        return Command::SUCCESS;
    }
}
