<?php

namespace App\Repository;

use App\Defaults;
use App\Entity\Fossil;
use App\Repository\FossilRepository\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fossil>
 *
 * @method Fossil|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Fossil|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Fossil> findAll()
 * @method array<Fossil> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FossilRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Filter $filter,
    ) {
        parent::__construct($registry, Fossil::class);
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function getColumnCount(array $filters = []): int
    {
        $queryBuilder = $this->createQueryBuilder('fossil')
            ->select('COUNT(fossil.id)');

        $this->filter->addFilter($filters, $queryBuilder);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array<string, mixed> $filters
     *
     * @return array<int, Fossil>
     */
    public function getSearchResult(int $offset, array $filters = []): array
    {
        $queryBuilder = $this->createQueryBuilder('fossil')
            ->select(['fossil']);

        $this->filter->addFilter($filters, $queryBuilder);

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults(Defaults::QUERY_LIMIT)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getExportList(int $limit, int $offset): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('fossil')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    /**
     * @return array<int, Fossil>
     */
    public function getLatestFossils(): array
    {
        return $this->createQueryBuilder('fossil')
            ->select(['fossil'])
            ->orderBy('fossil.createdAt', 'DESC')
            ->setMaxResults(Defaults::LATEST_FOSSILS_COUNT)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, Fossil>
     */
    public function getLatestChangedFossils(): array
    {
        return $this->createQueryBuilder('fossil')
            ->select(['fossil'])
            ->orderBy('fossil.updatedAt', 'DESC')
            ->setMaxResults(Defaults::LATEST_FOSSILS_COUNT)
            ->getQuery()
            ->getResult();
    }
}
