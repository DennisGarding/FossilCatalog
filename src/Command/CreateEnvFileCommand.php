<?php

namespace App\Command;

use App\Installation\InstallationDataService;
use App\Static\Installation\DotEnvFile;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-env-file',
    aliases: ['a:cef'],
    description: 'Creates the .env file for the Fossil Catalog application.',
    hidden: false,
)]
class CreateEnvFileCommand extends Command
{
    public function __construct(
        private readonly InstallationDataService $installationDataService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create Installation Data...');
        $installationData = $this->installationDataService->getInstallationData();

        $output->writeln('Create .env File...');
        if (!DotEnvFile::createDonEnvFile($installationData)) {
            throw new \RuntimeException('Could not create .env file.');
        }

        return Command::SUCCESS;
    }
}
