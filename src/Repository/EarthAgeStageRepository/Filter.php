<?php

namespace App\Repository\EarthAgeStageRepository;

use App\Repository\EarthAgeStageRepository\FilterHandler\CustomFilter;
use App\Repository\EarthAgeStageRepository\FilterHandler\SearchTermFilter;
use App\Repository\EarthAgeStageRepository\FilterHandler\SeriesFilter;
use App\Repository\FilterHandlerInterface;
use Doctrine\ORM\QueryBuilder;

class Filter
{
    /**
     * @var array<string, FilterHandlerInterface>
     */
    private array $filters = [];

    public function __construct(
        private readonly CustomFilter     $customFilter,
        private readonly SearchTermFilter $searchTermFilter,
        private readonly SeriesFilter     $seriesFilter,
    ) {
        $this->filters = [
            $this->customFilter->supports() => $this->customFilter,
            $this->searchTermFilter->supports() => $this->searchTermFilter,
            $this->seriesFilter->supports() => $this->seriesFilter,
        ];
    }

    /**
     * @param array<string, mixed> $filter
     */
    public function addFilter(array $filter, QueryBuilder $queryBuilder): void
    {
        foreach ($filter as $key => $value) {
            if (!isset($this->filters[$key])) {
                continue;
            }

            $this->filters[$key]->addFilter($filter, $queryBuilder);
        }
    }
}