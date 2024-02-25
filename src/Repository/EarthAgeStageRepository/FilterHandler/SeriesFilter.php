<?php

namespace App\Repository\EarthAgeStageRepository\FilterHandler;

use App\Repository\FilterHandlerInterface;
use Doctrine\ORM\QueryBuilder;

class SeriesFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'series';

    public function supports(): string
    {
        return self::FILTER_NAME;
    }

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void
    {
        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $queryBuilder->andWhere('earthAgeStage.earthAgeSeriesId = :seriesId')
            ->setParameter('seriesId', $filters[self::FILTER_NAME]);
    }
}
