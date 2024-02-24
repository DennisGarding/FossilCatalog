<?php

namespace App\Tests\Unit\Installation\EarthAges;

use App\Installation\EarthAges\System;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class SystemTest extends TestCase
{
    public function testGet(): void
    {
        $system = new System($this->createTranslator());
        $expected = <<<SQL
INSERT INTO `earth_age_system` (`id`, `name`, `active`, `custom`)
VALUES (1, 'Quartaer', false, false),
       (2, 'Neogen', false, false),
       (3, 'Paleogen', false, false),
       (4, 'Kreide', true, false),
       (5, 'Jura', true, false),
       (6, 'Trias', true, false),
       (7, 'Perm', true, false),
       (8, 'Karbon', true, false),
       (9, 'Devon', true, false),
       (10, 'Silur', true, false),
       (11, 'Ordovizium', true, false),
       (12, 'Kambrium', true, false),
       (13, 'Präkambrium', true, false);
SQL;

        static::assertSame($expected, $system->getSql());
    }

    private function createTranslator(): TranslatorInterface
    {
        $translatorMock = $this->createMock(TranslatorInterface::class);
        $translatorMock->method('trans')->willReturnMap([
            ['installation.system.quartaer', [], null, null, 'Quartaer'],
            ['installation.system.neogen', [], null, null, 'Neogen'],
            ['installation.system.paleogen', [], null, null, 'Paleogen'],
            ['installation.system.cretaceous', [], null, null, 'Kreide'],
            ['installation.system.jurassic', [], null, null, 'Jura'],
            ['installation.system.triassic', [], null, null, 'Trias'],
            ['installation.system.permian', [], null, null, 'Perm'],
            ['installation.system.carboniferous', [], null, null, 'Karbon'],
            ['installation.system.devonian', [], null, null, 'Devon'],
            ['installation.system.silurian', [], null, null, 'Silur'],
            ['installation.system.ordovician', [], null, null, 'Ordovizium'],
            ['installation.system.cambrian', [], null, null, 'Kambrium'],
            ['installation.system.precambrian', [], null, null, 'Präkambrium'],
        ]);

        return $translatorMock;
    }
}