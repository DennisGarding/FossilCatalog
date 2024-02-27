<?php

namespace App\Command;

use App\Installation\InstallationDataService;
use App\Static\Installation\PDOConnection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:prepare-test-database',
    aliases: ['a:ptd'],
    description: 'Prepares the test database for the Fossil Catalog application.',
    hidden: false,
)]
class PrepareTestDatabaseCommand extends Command
{
    public function __construct(
        private readonly InstallationDataService $installationDataService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create test database...');

        $installationData = $this->installationDataService->getInstallationData();
        $connection = PDOConnection::createPDOConnection($installationData);

        $connection->exec('DROP DATABASE IF EXISTS fossilCatalog_test;');
        $connection->exec('CREATE DATABASE IF NOT EXISTS fossilCatalog_test;');

        $sql = file_get_contents(__DIR__ . '/../../tests/_setup/fossilCatalog.sql');
        if (!is_string($sql)) {
            throw new \RuntimeException('Could not read test data sql file.');
        }

        $connection->exec('USE fossilCatalog_test; ' . $sql);

        return Command::SUCCESS;
    }
}
