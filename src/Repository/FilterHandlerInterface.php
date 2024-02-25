<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface FilterHandlerInterface
{
    public function supports(): string;

    /**
     * @param array<string, mixed>|null   $filters
     */
    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void;
}