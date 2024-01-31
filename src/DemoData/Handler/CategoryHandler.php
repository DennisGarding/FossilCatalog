<?php

namespace App\DemoData\Handler;

use App\DemoData\EntityOptions;
use App\DemoData\Random;
use App\DemoData\Random\NameOptions;
use App\DemoData\Random\StringOptions;
use App\DemoData\Random\StringOptions\Numbers;
use App\DemoData\Random\StringOptions\UpperCaseLetters;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryHandler implements HandlerInterface
{
    private const LIST = [
        'Kreide',
        'Jura',
        'Trias',
        'Perm',
        'Karbon',
        'Devon',
        'Silur',
        'Ordovizium',
        'Kambrium',
        'Ammoniten',
        'Brachiopoden',
        'Bryozoen',
        'Cephalopoden',
        'Conodonten',
        'Korallen',
        'Muscheln',
        'Gastropoden',
        'Trilobiten',
        'Crinoiden',
        'Echinoiden',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function supports(): string
    {
        return Category::class;
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

            $tag = new Category();
            $tag->setName($name);

            $this->entityManager->persist($tag);

            $counter++;
        }

        $this->entityManager->flush();
    }
}