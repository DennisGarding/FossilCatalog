<?php

namespace App\Repository;

use App\Entity\EarthAgeSeries;
use App\Repository\EarthAgeSeriesRepository\Filter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EarthAgeSeries>
 *
 * @method EarthAgeSeries|null   find($id, $lockMode = null, $lockVersion = null)
 * @method EarthAgeSeries|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<EarthAgeSeries> findAll()
 * @method array<EarthAgeSeries> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EarthAgeSeriesRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Filter $filter,
    ) {
        parent::__construct($registry, EarthAgeSeries::class);
    }

    /**
     * @param array<int> $ids
     *
     * @return array<int, array<string, mixed>>
     */
    public function findNamesById(array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSeries')
            ->select('earthAgeSeries.name')
            ->where('earthAgeSeries.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::INTEGER);

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }

    /**
     * @return array<int, EarthAgeSeries>
     */
    public function findBySystemId(int $systemId): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSeries')
            ->where('earthAgeSeries.earthAgeSystemId = :systemId')
            ->setParameter('systemId', $systemId);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array<int> $systemIds
     *
     * @return array<int, EarthAgeSeries>
     */
    public function findBySystemIds(array $systemIds): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSeries')
            ->where('earthAgeSeries.earthAgeSystemId IN (:systemIds)')
            ->setParameter('systemIds', $systemIds, ArrayParameterType::INTEGER);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param array<string, mixed> $filter
     *
     * @return array<int, EarthAgeSeries>
     */
    public function findByFilter(array $filter): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSeries');
        $this->filter->addFilter($filter, $queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<int, EarthAgeSeries>
     */
    public function findUsed(): array
    {
        $ids = $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select('DISTINCT series.id')
            ->from('earth_age_series', 'series')
            ->join('series', 'fossil', 'fossil', 'fossil.ea_series_id = series.id')
            ->where('fossil.ea_series_id IS NOT NULL')
            ->executeQuery()
            ->fetchFirstColumn();

        return $this->createQueryBuilder('earthAgeSeries')
            ->where('earthAgeSeries.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::INTEGER)
            ->getQuery()
            ->getResult();
    }

    public function getSeries(bool $isCreate, ?string $id): ?EarthAgeSeries
    {
        if ($isCreate === false && $id === null) {
            return null;
        }

        if ($isCreate === true) {
            $system = new EarthAgeSeries();
            $system->setCustom(true);

            return $system;
        }

        // @phpstan-ignore-next-line
        if ($id === null) {
            return null;
        }

        return $this->find($id);
    }

    public function save(EarthAgeSeries $earthAgeSystem): void
    {
        $this->_em->persist($earthAgeSystem);
        $this->_em->flush();
    }

    public function delete(EarthAgeSeries $earthAgeSystem): void
    {
        $this->_em->remove($earthAgeSystem);
        $this->_em->flush();
    }

    public function getColumnCount(): int
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSeries')
            ->select(['COUNT(earthAgeSeries.id)']);

        $result = $queryBuilder->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        return (int) $result;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getExportList(int $limit, int $offset): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('earth_age_series')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
