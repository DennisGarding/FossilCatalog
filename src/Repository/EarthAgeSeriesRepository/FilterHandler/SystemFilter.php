<?php

namespace App\Repository\EarthAgeSeriesRepository\FilterHandler;

use App\Repository\FilterHandlerInterface;
use Doctrine\ORM\QueryBuilder;

class SystemFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'system';

    public function supports(): string
    {
        return self::FILTER_NAME;
    }

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void
    {
        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $queryBuilder->andWhere('earthAgeSeries.earthAgeSystemId = :systemId')
            ->setParameter('systemId', $filters[self::FILTER_NAME]);
    }
}