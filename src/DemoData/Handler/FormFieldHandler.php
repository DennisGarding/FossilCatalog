<?php

namespace App\DemoData\Handler;

use App\DemoData\EntityOptions;
use App\DemoData\Random;
use App\DemoData\Random\StringOptions;
use App\DemoData\Random\StringOptions\Numbers;
use App\DemoData\Random\StringOptions\UpperCaseLetters;
use App\Entity\FossilFormField;
use App\FossilFormField\FossilFormFieldDefaults;
use Doctrine\ORM\EntityManagerInterface;

class FormFieldHandler implements HandlerInterface
{
    private const LIST = [
        'Preis',
        'Qualität',
        'Anmerkungen',
        'Lagerort',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function supports(): string
    {
        return FossilFormField::class;
    }

    public function create(EntityOptions $options): void
    {
        $counter = 0;

        foreach (self::LIST as $name) {
            $nameSuffix = Random::string(new StringOptions(4, [new Numbers(), new UpperCaseLetters()]));
            $name = $name . '__' . $nameSuffix;

            $fossilFormField = new FossilFormField();
            $fossilFormField->setFieldOrder(300 + $counter + 1);
            $fossilFormField->setFieldName(strtolower($name));
            $fossilFormField->setFieldLabel($name);
            $fossilFormField->setFieldType(FossilFormFieldDefaults::TYPE_TEXT);
            $fossilFormField->setAllowBlank(true);
            $fossilFormField->setIsRequiredDefault(false);
            $fossilFormField->setActive(true);
            $fossilFormField->setShowAlways(false);
            $fossilFormField->setFieldGroup(FossilFormFieldDefaults::GROUP_OTHER);
            $fossilFormField->setShowInOverview(false);

            $this->entityManager->persist($fossilFormField);

            $counter++;
        }

        $this->entityManager->flush();
    }
}
