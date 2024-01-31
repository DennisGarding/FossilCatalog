<?php

namespace App\Repository\EarthAgeStageRepository\FilterHandler;

use App\Repository\FilterHandlerInterface;
use Doctrine\ORM\QueryBuilder;

class SearchTermFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'searchTerm';

    public function supports(): string
    {
        return self::FILTER_NAME;
    }

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void
    {
        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $queryBuilder->andWhere('earthAgeStage.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $filters[self::FILTER_NAME] . '%');
    }
}
