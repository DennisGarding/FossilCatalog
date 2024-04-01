<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\Repository\FilterHandlerInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\QueryBuilder;

class StageFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'stage';

    public function supports(): string
    {
        return self::FILTER_NAME;
    }

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void
    {
        if (!isset($filters[self::FILTER_NAME])) {
            return;
        }

        if (!is_array($filters[self::FILTER_NAME])) {
            return;
        }

        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $queryBuilder->andWhere('fossil.eaStage IN (:stage)')
            ->setParameter('stage', $filters[self::FILTER_NAME], ArrayParameterType::INTEGER);
    }
}
