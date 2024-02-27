<?php

namespace App\Command;

use App\Installation\InstallationDataService;
use App\Static\Installation\PDOConnection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-database',
    aliases: ['a:cd'],
    description: 'Creates the database for the Fossil Catalog application.',
    hidden: false,
)]
class CreateDatabaseCommand extends Command
{
    public function __construct(
        private readonly InstallationDataService $installationDataService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create database for application...');
        $installationData = $this->installationDataService->getInstallationData();

        $connection = PDOConnection::createPDOConnection($installationData);

        $connection->exec('DROP DATABASE IF EXISTS fossilCatalog;');
        $connection->exec('CREATE DATABASE IF NOT EXISTS fossilCatalog;');

        return Command::SUCCESS;
    }
}
