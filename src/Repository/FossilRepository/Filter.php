<?php

namespace App\Repository\FossilRepository;

use App\Repository\FilterHandlerInterface;
use App\Repository\FossilRepository\FilterHandler\CategoryFilter;
use App\Repository\FossilRepository\FilterHandler\SearchTermFilter;
use App\Repository\FossilRepository\FilterHandler\SeriesFilter;
use App\Repository\FossilRepository\FilterHandler\StageFilter;
use App\Repository\FossilRepository\FilterHandler\SystemFilter;
use App\Repository\FossilRepository\FilterHandler\TagFilter;
use Doctrine\ORM\QueryBuilder;

class Filter
{
    /**
     * @var array<string, FilterHandlerInterface>
     */
    private array $filters = [];

    public function __construct(
        private readonly CategoryFilter $categoryFilter,
        private readonly TagFilter      $tagFilter,
        private readonly SystemFilter   $systemFilter,
        private readonly SeriesFilter   $seriesFilter,
        private readonly StageFilter    $stageFilter,
        private readonly SearchTermFilter $searchTermFilter,
    ) {
        $this->filters = [
            $this->categoryFilter->supports() => $this->categoryFilter,
            $this->tagFilter->supports() => $this->tagFilter,
            $this->systemFilter->supports() => $this->systemFilter,
            $this->seriesFilter->supports() => $this->seriesFilter,
            $this->stageFilter->supports() => $this->stageFilter,
            $this->searchTermFilter->supports() => $this->searchTermFilter,
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
