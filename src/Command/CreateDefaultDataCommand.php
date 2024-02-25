<?php

namespace App\Command;

use App\Installation\CreateUserService;
use App\Installation\EarthAges\Series;
use App\Installation\EarthAges\Stage;
use App\Installation\EarthAges\System;
use App\Installation\InstallationDataService;
use App\Static\Installation\InstallationData;
use App\Static\Installation\LockFile;
use App\Static\Installation\PDOConnection;
use App\Translations\TranslationService;
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
        private readonly CreateUserService $createUserService,
        private readonly TranslationService $translationService,
        private readonly System $system,
        private readonly Series $series,
        private readonly Stage $stage,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $installationData = $this->installationDataService->getInstallationData();

        $output->writeln('Establish Database Connection...');
        $connection = PDOConnection::createPDOConnection($installationData);

        $output->writeln('Install default data...');
        $connection->exec(sprintf('USE %s;', $installationData->getDatabaseName()));
        $connection->exec($this->system->getSql());
        $connection->exec($this->series->getSql());
        $connection->exec($this->stage->getSql());

        $sql = file_get_contents(__DIR__ . '/../Controller/Installation/SQL/Default.sql');
        if (!is_string($sql)) {
            throw new \RuntimeException('Could not read default data sql file.');
        }
        $connection->exec($sql);

        $this->createUser($output, $installationData);

        $output->writeln('Create translations...');
        $this->translationService->moveToPublic();

        $output->writeln('Create lock file...');
        LockFile::createInstallationLockFile();

        return Command::SUCCESS;
    }

    public function createUser(OutputInterface $output, InstallationData $installationData): void
    {
        $output->writeln('Create demo user...');
        $user = $this->createUserService->createUser($installationData->getUserEmail(), $installationData->getUserPassword());
        $this->createUserService->saveUser($user);

        $output->writeln('Email: test@example.com');
        $output->writeln('Password: test1234');
    }
}
