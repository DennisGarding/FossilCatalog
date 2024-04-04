<?php

namespace App\Repository\FossilRepository\FilterHandler;

use App\FossilFormField\FossilFieldMapper;
use App\Repository\FilterHandlerInterface;
use App\Repository\FossilFormFieldRepository;
use Doctrine\ORM\QueryBuilder;

class SearchTermFilter implements FilterHandlerInterface
{
    private const FILTER_NAME = 'searchTerm';

    public function __construct(
        private readonly FossilFormFieldRepository $fossilFormFieldRepository,
        private readonly FossilFieldMapper $fossilFieldMapper,
    ) {}

    public function supports(): string
    {
        return self::FILTER_NAME;
    }

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void
    {
        if (empty($filters[self::FILTER_NAME])) {
            return;
        }

        $formFields = $this->fossilFormFieldRepository->findFilterableFields();

        foreach ($formFields as $formField) {
            if ($formField->getFieldName() === 'eaSystem') {
                $queryBuilder->join('fossil.eaSystem', 'eaSystem');
                $queryBuilder->orWhere('eaSystem.name LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $filters[self::FILTER_NAME] . '%');
                continue;
            }

            if ($formField->getFieldName() === 'eaSeries') {
                $queryBuilder->join('fossil.eaSeries', 'eaSeries');
                $queryBuilder->orWhere('eaSeries.name LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $filters[self::FILTER_NAME] . '%');
                continue;
            }

            if ($formField->getFieldName() === 'eaStage') {
                $queryBuilder->join('fossil.eaStage', 'eaStage');
                $queryBuilder->orWhere('eaStage.name LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $filters[self::FILTER_NAME] . '%');
                continue;
            }

            $queryBuilder->orWhere(sprintf('fossil.%s LIKE :searchTerm', $this->fossilFieldMapper->mapProperty($formField->getFieldName())))
                ->setParameter('searchTerm', '%' . $filters[self::FILTER_NAME] . '%');
        }
    }
}
