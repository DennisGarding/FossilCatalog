<?php

namespace App\Command;

use App\Installation\CreateUserService;
use App\Installation\InstallationDataService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-demo-user',
    aliases: ['a:cdu'],
    description: 'Creates a demo user for the Fossil Catalog application.',
    hidden: false,
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private readonly InstallationDataService $installationDataService,
        private readonly CreateUserService $createUserService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create demo user...');

        $installationData = $this->installationDataService->getInstallationData();

        $user = $this->createUserService->createUser($installationData->getUserEmail(), $installationData->getUserPassword());
        $this->createUserService->saveUser($user);

        $output->writeln('Email: test@example.com');
        $output->writeln('Password: test1234');

        return Command::SUCCESS;
    }
}
