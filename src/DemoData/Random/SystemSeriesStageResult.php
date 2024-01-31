<?php

namespace App\DemoData\Random;

use App\Entity\EarthAgeSeries;
use App\Entity\EarthAgeStage;
use App\Entity\EarthAgeSystem;

class SystemSeriesStageResult
{
    public function __construct(
        private readonly EarthAgeSystem $system,
        private readonly EarthAgeSeries $series,
        private readonly EarthAgeStage  $stage
    ) {}

    public function getSystem(): EarthAgeSystem
    {
        return $this->system;
    }

    public function getSeries(): EarthAgeSeries
    {
        return $this->series;
    }

    public function getStage(): EarthAgeStage
    {
        return $this->stage;
    }
}