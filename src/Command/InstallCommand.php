<?php

namespace App\Command;

use App\Installation\CreateUserService;
use App\Installation\EarthAges\Series;
use App\Installation\EarthAges\Stage;
use App\Installation\EarthAges\System;
use App\Static\Installation\DotEnvFile;
use App\Static\Installation\Installation;
use App\Static\Installation\LockFile;
use App\Static\Installation\PDOConnection;
use App\Translations\TranslationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:install',
    description: 'Installs the Fossil Catalog application.',
    hidden: false,
)]
class InstallCommand extends Command
{
    public function __construct(
        private readonly CreateUserService  $createUserService,
        private readonly TranslationService $translationService,
        private readonly System             $system,
        private readonly Series             $series,
        private readonly Stage              $stage,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create Installation Data...');
        $installationData = Installation::createInstallationData([
            'databaseName' => 'fossilCatalog',
            'databaseUsername' => 'root',
            'databasePassword' => 'root',
            'databaseHost' => 'mysql',
            'databasePort' => 3306,
            'userEmail' => 'test@example.com',
            'userPassword' => 'test1234',
            'userPasswordConfirm' => 'test1234',
            'language' => 'de',
        ]);

        $output->writeln('Create .env File...');
        DotEnvFile::createDonEnvFile($installationData);

        $output->writeln('Establish Database Connection...');
        $connection = PDOConnection::createPDOConnection($installationData);

        $output->writeln('Drop Database...');
        $connection->exec('DROP DATABASE IF EXISTS fossilCatalog;');

        $output->writeln('Create Database...');
        $connection->exec('CREATE DATABASE IF NOT EXISTS fossilCatalog;');

        $output->writeln('Create Database Schema...');
        $greetInput = new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true,
        ]);

        $returnCode = $this->getApplication()->doRun($greetInput, $output);
        if ($returnCode !== Command::SUCCESS) {
            throw new \RuntimeException('Could not create database schema.');
        }

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

        $output->writeln('Create demo user...');
        $user = $this->createUserService->createUser($installationData->getUserEmail(), $installationData->getUserPassword());
        $this->createUserService->saveUser($user);

        $output->writeln('Email: test@example.com');
        $output->writeln('Password: test1234');

        $output->writeln('Create translations...');
        $this->translationService->moveToPublic();

        $output->writeln('Create lock file...');
        LockFile::createInstallationLockFile();

        return Command::SUCCESS;
    }
}