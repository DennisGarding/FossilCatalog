<?php

namespace App\SystemSeriesStage;

use App\Entity\EarthAgeSeries;
use App\Entity\EarthAgeStage;
use App\Entity\EarthAgeSystem;

class ActiveSystemSeriesStageResult
{
    /**
     * @param array<EarthAgeSystem> $activeSystems
     * @param array<EarthAgeSeries> $activeSeries
     * @param array<EarthAgeStage>  $activeStages
     */
    public function __construct(
        private readonly array $activeSystems,
        private readonly array $activeSeries,
        private readonly array $activeStages,
    ) {}

    /**
     * @return array<EarthAgeSystem>
     */
    public function getActiveSystems(): array
    {
        return $this->activeSystems;
    }

    /**
     * @return array<EarthAgeSeries>
     */
    public function getActiveSeries(): array
    {
        return $this->activeSeries;
    }

    /**
     * @return array<EarthAgeStage>
     */
    public function getActiveStages(): array
    {
        return $this->activeStages;
    }
}
