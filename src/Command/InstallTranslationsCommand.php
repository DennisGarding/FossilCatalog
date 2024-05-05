<?php

namespace App\Command;

use App\Translations\TranslationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:install-translations',
    aliases: ['app:translate', 'a:it', 'a:t'],
    description: 'Installs the translations for the frontend',
    hidden: false,
)]
class InstallTranslationsCommand extends Command
{
    public function __construct(
        private readonly TranslationService $translationService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create translations...');

        $this->translationService->moveToPublic('en');

        return Command::SUCCESS;
    }
}
