<?php

namespace App\Command;

use App\DemoData\DemoDataFactory;
use App\DemoData\EntityOptions;
use App\Entity\Category;
use App\Entity\Fossil;
use App\Entity\FossilFormField;
use App\Entity\Tag;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:demo-data:create',
    description: 'Creates demo data for the application.',
    hidden: false,
    aliases: ['app:demo']
)]
class CreateDemoDataCommand extends Command
{
    public function __construct(
        private readonly DemoDataFactory $demoDataFactory,
        private readonly Connection      $connection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Clearing Database...');
        $this->connection->executeQuery(<<<SQL
            SET FOREIGN_KEY_CHECKS=0;
            
            TRUNCATE category;
            TRUNCATE tag;
            TRUNCATE fossil;
            TRUNCATE fossil_category;
            TRUNCATE fossil_tag;
            TRUNCATE fossil_image;
            TRUNCATE image;
            SET FOREIGN_KEY_CHECKS=1;
SQL
        );

        $output->writeln('Creating Form Fields...');
        // set amount to 0 because inside the handler we do not consider the amount
        $this->demoDataFactory->create([new EntityOptions(FossilFormField::class, 0)]);

        $output->writeln('Creating Tags...');
        $this->demoDataFactory->create([new EntityOptions(Tag::class, 100)]);

        $output->writeln('Creating Categories...');
        $this->demoDataFactory->create([new EntityOptions(Category::class, 100)]);

        $output->writeln('Creating Fossils...');
        $counter = 0;
        try {
            while ($counter < 10000) {
                $this->demoDataFactory->create([new EntityOptions(Fossil::class, 1000)]);
                $counter += 1000;
                $output->writeln(sprintf('%d Fossils created', $counter));
            }
        } catch (\Throwable $th) {
            $output->writeln($th->getMessage());
            $output->writeln($th->getTraceAsString());
        }

        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}