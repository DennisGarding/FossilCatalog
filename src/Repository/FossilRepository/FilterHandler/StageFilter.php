<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\Repository\EarthAgeStageRepository;
use App\Repository\FilterHandlerInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\QueryBuilder;

class StageFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'stage';

    public function __construct(
        private readonly EarthAgeStageRepository $earthAgeStageRepository,
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

        $stages = $this->earthAgeStageRepository->findNamesById($filters[self::FILTER_NAME]);
        if (empty($stages)) {
            return;
        }

        $queryBuilder->andWhere('fossil.earthAgeStage IN (:stage)')
            ->setParameter('stage', $stages, ArrayParameterType::STRING);
    }
}
