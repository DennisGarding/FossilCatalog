<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface FilterHandlerInterface
{
    public function supports(): string;

    public function addFilter(?array $filters, QueryBuilder $queryBuilder): void;
}