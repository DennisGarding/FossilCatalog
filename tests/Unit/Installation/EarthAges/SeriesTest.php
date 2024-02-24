<?php

namespace App\Tests\Unit\Installation\EarthAges;

use App\Installation\EarthAges\Series;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class SeriesTest extends TestCase
{
    public function testGet(): void
    {
        $system = new Series($this->createTranslator());
        $expected = <<<SQL
INSERT INTO `earth_age_series` (`id`, `name`, `earth_age_system_id`, `custom`)
VALUES (1, 'Holocene', 1, false),
       (2, 'Pleistocene', 1, false),
       (3, 'Pliocene', 2, false),
       (4, 'Miocene', 2, false),
       (5, 'Oligocene', 3, false),
       (6, 'Eocene', 3, false),
       (7, 'Paleocene', 3, false),
       (8, 'Upper Cretaceous', 4, false),
       (9, 'Lower Cretaceous', 4, false),
       (10, 'Upper Jurassic', 5, false),
       (11, 'Middle Jurassic', 5, false),
       (12, 'Lower Jurassic', 5, false),
       (13, 'Upper Triassic', 6, false),
       (14, 'Middle Triassic', 6, false),
       (15, 'Lower Triassic', 6, false),
       (16, 'Lopingian', 7, false),
       (17, 'Guadalupian', 7, false),
       (18, 'Cisuralian', 7, false),
       (19, 'Pennsylvanian', 8, false),
       (20, 'Mississippian', 8, false),
       (21, 'Upper Devonian', 9, false),
       (22, 'Middle Devonian', 9, false),
       (23, 'Lower Devonian', 9, false),
       (24, 'Pridoli', 10, false),
       (25, 'Ludlow', 10, false),
       (26, 'Wenlock', 10, false),
       (27, 'Llandovery', 10, false),
       (28, 'Upper Ordovician', 11, false),
       (29, 'Middle Ordovician', 11, false),
       (30, 'Lower Ordovician', 11, false),
       (31, 'Furongian', 12, false),
       (32, 'Miaolingian', 12, false),
       (33, '2nd Series', 12, false),
       (34, 'Terreneuvian', 12, false);
SQL;

        static::assertSame($expected, $system->getSql());
    }

    private function createTranslator(): TranslatorInterface
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')->willReturnMap([
            ['installation.series.holocene', [], null, null, 'Holocene'],
            ['installation.series.pleistocene', [], null, null, 'Pleistocene'],
            ['installation.series.pliocene', [], null, null, 'Pliocene'],
            ['installation.series.miocene', [], null, null, 'Miocene'],
            ['installation.series.oligocene', [], null, null, 'Oligocene'],
            ['installation.series.eocene', [], null, null, 'Eocene'],
            ['installation.series.paleocene', [], null, null, 'Paleocene'],
            ['installation.series.upperCretaceous', [], null, null, 'Upper Cretaceous'],
            ['installation.series.lowerCretaceous', [], null, null, 'Lower Cretaceous'],
            ['installation.series.upperJurassic', [], null, null, 'Upper Jurassic'],
            ['installation.series.middleJurassic', [], null, null, 'Middle Jurassic'],
            ['installation.series.lowerJurassic', [], null, null, 'Lower Jurassic'],
            ['installation.series.upperTriassic', [], null, null, 'Upper Triassic'],
            ['installation.series.middleTriassic', [], null, null, 'Middle Triassic'],
            ['installation.series.lowerTriassic', [], null, null, 'Lower Triassic'],
            ['installation.series.lopingian', [], null, null, 'Lopingian'],
            ['installation.series.guadalupian', [], null, null, 'Guadalupian'],
            ['installation.series.cisuralian', [], null, null, 'Cisuralian'],
            ['installation.series.pennsylvanian', [], null, null, 'Pennsylvanian'],
            ['installation.series.mississippian', [], null, null, 'Mississippian'],
            ['installation.series.upperDevonian', [], null, null, 'Upper Devonian'],
            ['installation.series.middleDevonian', [], null, null, 'Middle Devonian'],
            ['installation.series.lowerDevonian', [], null, null, 'Lower Devonian'],
            ['installation.series.pridoli', [], null, null, 'Pridoli'],
            ['installation.series.ludlow', [], null, null, 'Ludlow'],
            ['installation.series.wenlock', [], null, null, 'Wenlock'],
            ['installation.series.llandovery', [], null, null, 'Llandovery'],
            ['installation.series.upperOrdovician', [], null, null, 'Upper Ordovician'],
            ['installation.series.middleOrdovician', [], null, null, 'Middle Ordovician'],
            ['installation.series.lowerOrdovician', [], null, null, 'Lower Ordovician'],
            ['installation.series.furongian', [], null, null, 'Furongian'],
            ['installation.series.miaolingian', [], null, null, 'Miaolingian'],
            ['installation.series.2ndSeries', [], null, null, '2nd Series'],
            ['installation.series.terreneuvian', [], null, null, 'Terreneuvian'],
        ]);

        return $translatorMock;
    }
}