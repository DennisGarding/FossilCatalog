<?php

namespace App\Installation\EarthAges;

use Symfony\Contracts\Translation\TranslatorInterface;

class System
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    public function getSql(): string
    {
        $sql = <<<SQL
INSERT INTO `earth_age_system` (`id`, `name`, `active`, `custom`)
VALUES (1, 'installation.system.quartaer', false, false),
       (2, 'installation.system.neogen', false, false),
       (3, 'installation.system.paleogen', false, false),
       (4, 'installation.system.cretaceous', true, false),
       (5, 'installation.system.jurassic', true, false),
       (6, 'installation.system.triassic', true, false),
       (7, 'installation.system.permian', true, false),
       (8, 'installation.system.carboniferous', true, false),
       (9, 'installation.system.devonian', true, false),
       (10, 'installation.system.silurian', true, false),
       (11, 'installation.system.ordovician', true, false),
       (12, 'installation.system.cambrian', true, false),
       (13, 'installation.system.precambrian', true, false);
SQL;

        foreach ($this->getTranslationKeys() as $translationKey) {

            $sql = str_replace($translationKey, $this->translator->trans($translationKey), $sql);
        }

        return $sql;
    }

    /**
     * @return array<int, string>
     */
    private function getTranslationKeys(): array
    {
        return [
            'installation.system.quartaer',
            'installation.system.neogen',
            'installation.system.paleogen',
            'installation.system.cretaceous',
            'installation.system.jurassic',
            'installation.system.triassic',
            'installation.system.permian',
            'installation.system.carboniferous',
            'installation.system.devonian',
            'installation.system.silurian',
            'installation.system.ordovician',
            'installation.system.cambrian',
            'installation.system.precambrian',
        ];
    }
}