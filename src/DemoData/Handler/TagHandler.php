<?php

namespace App\DemoData\Handler;

use App\DemoData\EntityOptions;
use App\DemoData\Random;
use App\DemoData\Random\NameOptions;
use App\DemoData\Random\StringOptions;
use App\DemoData\Random\StringOptions\Numbers;
use App\DemoData\Random\StringOptions\UpperCaseLetters;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;

class TagHandler implements HandlerInterface
{
    private const LIST = [
        'Aufwuchs',
        'Juvenil',
        'Pathologie',
        'Bioerosionsspuren',
        'Doppelklappig',
        'Kalzifiziert',
        'Flora',
        'Fauna',
        'Invertebraten',
        'Pyritisiert',
        'Spurenfossil',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function supports(): string
    {
        return Tag::class;
    }

    public function create(EntityOptions $options): void
    {
        $avoidList = [];
        $counter = 0;

        $nameOptions = new NameOptions(
            self::LIST,
            '__',
            new StringOptions(
                4,
                [
                    new Numbers(),
                    new UpperCaseLetters(),
                ]
            )
        );

        while ($counter < $options->amount) {
            $name = Random::name($nameOptions);
            if (in_array($name, $avoidList)) {
                continue;
            }

            $nameOptions->registerAvoid($name);

            $tag = new Tag();
            $tag->setName($name);

            $this->entityManager->persist($tag);

            $counter++;
        }

        $this->entityManager->flush();
    }
}