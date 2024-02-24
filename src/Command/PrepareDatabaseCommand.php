<?php

namespace App\Command;

use App\Installation\InstallationDataService;
use App\Static\Installation\DotEnvFile;
use App\Static\Installation\Installation;
use App\Static\Installation\PDOConnection;
use PDO;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:prepare-database',
    aliases: ['a:pd'],
    description: 'prepares the database for the Fossil Catalog application.',
    hidden: false,
)]
class PrepareDatabaseCommand extends Command
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
            throw new RuntimeException('Could not create .env file.');
        }

        $output->writeln('Establish Database Connection...');
        $connection = PDOConnection::createPDOConnection($installationData);

        $this->dropDatabase($output, $connection);
        $this->createDatabase($output, $connection);

        return Command::SUCCESS;
    }

    private function createDatabase(OutputInterface $output, PDO $connection): void
    {
        $output->writeln('Create Database...');
        $connection->exec('CREATE DATABASE IF NOT EXISTS fossilCatalog;');
        $connection->exec('CREATE DATABASE IF NOT EXISTS fossilCatalog_test;');
    }

    private function dropDatabase(OutputInterface $output, PDO $connection): void
    {
        $output->writeln('Drop Database...');
        $connection->exec('DROP DATABASE IF EXISTS fossilCatalog;');
        $connection->exec('DROP DATABASE IF EXISTS fossilCatalog_test;');
    }
}