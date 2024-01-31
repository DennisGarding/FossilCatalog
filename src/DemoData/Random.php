<?php

namespace App\DemoData;

use App\DemoData\Random\EntityObjectOptions;
use App\DemoData\Random\EntityOptions;
use App\DemoData\Random\LoremIpsum\LoremIpsum;
use App\DemoData\Random\NameOptions;
use App\DemoData\Random\StringOptions;
use App\Entity\EntityInterface;

class Random
{
    private const DIVIDER = '__';

    public static function name(NameOptions $nameOptions): string
    {
        $diff = array_diff($nameOptions->getNames(), $nameOptions->getAvoid());

        if (empty($diff)) {
            $diff = $nameOptions->getNames();
        }

        $randomIndex = array_rand($diff);

        return $diff[$randomIndex] . $nameOptions->getDivider() . self::string($nameOptions->getStringOptions());
    }

    public static function string(StringOptions $stringOptions): string
    {
        $characters = $stringOptions->getCharacters();
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $stringOptions->getLength(); $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function loremIpsum(): string
    {
        $dataSet = LoremIpsum::get();

        return $dataSet[array_rand($dataSet)];
    }

    /**
     * @param EntityObjectOptions $entityOptions
     * @return array<int, EntityInterface>
     */
    public static function entity(EntityObjectOptions $entityOptions): array
    {
        $counter = 0;
        $entities = [];
        while ($counter < $entityOptions->getLength()) {
            $entity = $entityOptions->getEntities()[array_rand($entityOptions->getEntities())];
            if (array_key_exists($entity->getId(), $entities)) {
                continue;
            }

            $entities[$entity->getId()] = $entity;
            $counter++;
        }

        return $entities;
    }

    public static function date(\DateTimeInterface $start, \DateTimeInterface $end): \DateTimeInterface
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());

        return (new \DateTime())->setTimestamp($randomTimestamp);
    }

    private static function createAvoidList(array $avoid): array
    {
        return array_map(function ($string) {
            $exploded = explode(self::DIVIDER, $string);

            return $exploded[0];
        }, $avoid);
    }
}