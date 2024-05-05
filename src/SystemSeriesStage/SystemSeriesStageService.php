<?php

namespace App\SystemSeriesStage;

use App\Repository\EarthAgeSeriesRepository;
use App\Repository\EarthAgeStageRepository;
use App\Repository\EarthAgeSystemRepository;

class SystemSeriesStageService
{
    public function __construct(
        private readonly EarthAgeSystemRepository $systemRepository,
        private readonly EarthAgeSeriesRepository $seriesRepository,
        private readonly EarthAgeStageRepository $stageRepository,
    ) {}

    public function findAllActive(): ActiveSystemSeriesStageResult
    {
        $activeSystems = $this->systemRepository->findAllActive();
        $activeSystemIds = array_map(fn ($system) => $system->getId(), $activeSystems);

        $activeSeries = $this->seriesRepository->findBySystemIds($activeSystemIds);
        $activeSeriesIds = array_map(fn ($series) => $series->getId(), $activeSeries);

        $activeStages = $this->stageRepository->findBySeriesIds($activeSeriesIds);

        return new ActiveSystemSeriesStageResult($activeSystems, $activeSeries, $activeStages);
    }

    public function findAllActiveUsed(): ActiveSystemSeriesStageResult
    {
        $activeSystems = $this->systemRepository->findAllActiveUsed();

        $activeSeries = $this->seriesRepository->findUsed();

        $activeStages = $this->stageRepository->findUsed();

        return new ActiveSystemSeriesStageResult($activeSystems, $activeSeries, $activeStages);
    }
}
