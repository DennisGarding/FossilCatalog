<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\Repository\EarthAgeSystemRepository;
use App\Repository\FilterHandlerInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\QueryBuilder;

class SystemFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'system';

    public function __construct(
        private readonly EarthAgeSystemRepository $earthAgeSystemRepository,
    ) {}

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

        $systems = $this->earthAgeSystemRepository->findNamesById($filters[self::FILTER_NAME]);
        if (empty($systems)) {
            return;
        }

        $queryBuilder->andWhere('fossil.earthAgeSystem IN (:system)')
            ->setParameter('system', $systems, ArrayParameterType::STRING);
    }
}
