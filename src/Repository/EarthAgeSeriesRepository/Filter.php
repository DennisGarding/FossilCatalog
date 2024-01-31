<?php

namespace App\Repository\EarthAgeSeriesRepository;

use App\Repository\EarthAgeSeriesRepository\FilterHandler\CustomFilter;
use App\Repository\EarthAgeSeriesRepository\FilterHandler\SearchTermFilter;
use App\Repository\EarthAgeSeriesRepository\FilterHandler\SystemFilter;
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
        private readonly SystemFilter     $systemFilter,
    ) {
        $this->filters = [
            $this->customFilter->supports() => $this->customFilter,
            $this->searchTermFilter->supports() => $this->searchTermFilter,
            $this->systemFilter->supports() => $this->systemFilter,
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