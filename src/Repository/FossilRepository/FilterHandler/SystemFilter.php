<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\Repository\FilterHandlerInterface;
use Doctrine\DBAL\ArrayParameterType;
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
        if (!isset($filters[self::FILTER_NAME])) {
            return;
        }

        if (!is_array($filters[self::FILTER_NAME])) {
            return;
        }

        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $queryBuilder->andWhere('fossil.eaSystem IN (:system)')
            ->setParameter('system', $filters[self::FILTER_NAME], ArrayParameterType::INTEGER);
    }
}
