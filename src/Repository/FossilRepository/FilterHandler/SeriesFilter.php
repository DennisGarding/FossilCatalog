<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\Repository\EarthAgeSeriesRepository;
use App\Repository\FilterHandlerInterface;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\QueryBuilder;

class SeriesFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'series';

    public function __construct(
        private readonly EarthAgeSeriesRepository $earthAgeSeriesRepository,
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

        $series = $this->earthAgeSeriesRepository->findNamesById($filters[self::FILTER_NAME]);
        if (empty($series)) {
            return;
        }

        $queryBuilder->andWhere('fossil.earthAgeSeries IN (:series)')
            ->setParameter('series', $series, ArrayParameterType::STRING);
    }
}