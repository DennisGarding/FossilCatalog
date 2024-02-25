<?php

namespace App\Installation\EarthAges;

use Symfony\Contracts\Translation\TranslatorInterface;

class Series
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {}

    public function getSql(): string
    {
        $sql = <<<SQL
INSERT INTO `earth_age_series` (`id`, `name`, `earth_age_system_id`, `custom`)
VALUES (1, 'installation.series.holocene', 1, false),
       (2, 'installation.series.pleistocene', 1, false),
       (3, 'installation.series.pliocene', 2, false),
       (4, 'installation.series.miocene', 2, false),
       (5, 'installation.series.oligocene', 3, false),
       (6, 'installation.series.eocene', 3, false),
       (7, 'installation.series.paleocene', 3, false),
       (8, 'installation.series.upperCretaceous', 4, false),
       (9, 'installation.series.lowerCretaceous', 4, false),
       (10, 'installation.series.upperJurassic', 5, false),
       (11, 'installation.series.middleJurassic', 5, false),
       (12, 'installation.series.lowerJurassic', 5, false),
       (13, 'installation.series.upperTriassic', 6, false),
       (14, 'installation.series.middleTriassic', 6, false),
       (15, 'installation.series.lowerTriassic', 6, false),
       (16, 'installation.series.lopingian', 7, false),
       (17, 'installation.series.guadalupian', 7, false),
       (18, 'installation.series.cisuralian', 7, false),
       (19, 'installation.series.pennsylvanian', 8, false),
       (20, 'installation.series.mississippian', 8, false),
       (21, 'installation.series.upperDevonian', 9, false),
       (22, 'installation.series.middleDevonian', 9, false),
       (23, 'installation.series.lowerDevonian', 9, false),
       (24, 'installation.series.pridoli', 10, false),
       (25, 'installation.series.ludlow', 10, false),
       (26, 'installation.series.wenlock', 10, false),
       (27, 'installation.series.llandovery', 10, false),
       (28, 'installation.series.upperOrdovician', 11, false),
       (29, 'installation.series.middleOrdovician', 11, false),
       (30, 'installation.series.lowerOrdovician', 11, false),
       (31, 'installation.series.furongian', 12, false),
       (32, 'installation.series.miaolingian', 12, false),
       (33, 'installation.series.2ndSeries', 12, false),
       (34, 'installation.series.terreneuvian', 12, false);
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
            'installation.series.holocene',
            'installation.series.pleistocene',
            'installation.series.pliocene',
            'installation.series.miocene',
            'installation.series.oligocene',
            'installation.series.eocene',
            'installation.series.paleocene',
            'installation.series.upperCretaceous',
            'installation.series.lowerCretaceous',
            'installation.series.upperJurassic',
            'installation.series.middleJurassic',
            'installation.series.lowerJurassic',
            'installation.series.upperTriassic',
            'installation.series.middleTriassic',
            'installation.series.lowerTriassic',
            'installation.series.lopingian',
            'installation.series.guadalupian',
            'installation.series.cisuralian',
            'installation.series.pennsylvanian',
            'installation.series.mississippian',
            'installation.series.upperDevonian',
            'installation.series.middleDevonian',
            'installation.series.lowerDevonian',
            'installation.series.pridoli',
            'installation.series.ludlow',
            'installation.series.wenlock',
            'installation.series.llandovery',
            'installation.series.upperOrdovician',
            'installation.series.middleOrdovician',
            'installation.series.lowerOrdovician',
            'installation.series.furongian',
            'installation.series.miaolingian',
            'installation.series.2ndSeries',
            'installation.series.terreneuvian',
        ];
    }
}
