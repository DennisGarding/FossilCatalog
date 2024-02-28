<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-e2e-env-file',
    description: 'Creates the .env file for the Fossil Catalog e2e tests.',
    hidden: false,
)]
class CreateGitHubE2EDotEnvCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = <<<EOF
BASE_URL=http://localhost
EOF;

        file_put_contents(__DIR__ . '/../../tests/e2e/.env', $content);

        return Command::SUCCESS;
    }
}
