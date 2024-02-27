<?php

namespace App\Command;

use App\Installation\EarthAges\Series;
use App\Installation\EarthAges\Stage;
use App\Installation\EarthAges\System;
use App\Installation\InstallationDataService;
use App\Static\Installation\PDOConnection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-default-data',
    aliases: ['a:cdd'],
    description: 'Creates the default data for the Fossil Catalog application.',
    hidden: false,
)]
class CreateDefaultDataCommand extends Command
{
    public function __construct(
        private readonly InstallationDataService $installationDataService,
        private readonly System $system,
        private readonly Series $series,
        private readonly Stage $stage,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $installationData = $this->installationDataService->getInstallationData();

        $output->writeln('Install default data...');
        $connection = PDOConnection::createPDOConnection($installationData);

        $connection->exec(sprintf('USE %s;', $installationData->getDatabaseName()));
        $connection->exec($this->system->getSql());
        $connection->exec($this->series->getSql());
        $connection->exec($this->stage->getSql());

        $sql = file_get_contents(__DIR__ . '/../Controller/Installation/SQL/Default.sql');
        if (!is_string($sql)) {
            throw new \RuntimeException('Could not read default data sql file.');
        }
        $connection->exec($sql);

        return Command::SUCCESS;
    }
}
