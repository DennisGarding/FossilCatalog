<?php

namespace App\Repository;

use App\Entity\EarthAgeSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EarthAgeSystem>
 *
 * @method EarthAgeSystem|null   find($id, $lockMode = null, $lockVersion = null)
 * @method EarthAgeSystem|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<EarthAgeSystem> findAll()
 * @method array<EarthAgeSystem> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EarthAgeSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EarthAgeSystem::class);
    }

    /**
     * @param array<int> $ids
     *
     * @return array<int, array<string, mixed>>
     */
    public function findNamesById(array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSystem')
            ->select('earthAgeSystem.name')
            ->where('earthAgeSystem.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::INTEGER);

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }

    /**
     * @return array<int, EarthAgeSystem>
     */
    public function findAllActive()
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSystem')
            ->select('earthAgeSystem')
            ->where('earthAgeSystem.active = true');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return array<int, EarthAgeSystem>
     */
    public function findUsed(): array
    {
        $ids = $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select('DISTINCT systems.id')
            ->from('earth_age_system', 'systems')
            ->join('systems', 'fossil', 'fossil', 'fossil.ea_system_id = systems.id')
            ->where('fossil.ea_system_id IS NOT NULL')
            ->executeQuery()
            ->fetchFirstColumn();

        return $this->createQueryBuilder('earthAgeSystem')
            ->where('earthAgeSystem.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::INTEGER)
            ->getQuery()
            ->getResult();
    }

    public function getSystem(bool $isCreate, ?string $id): ?EarthAgeSystem
    {
        if ($isCreate === false && $id === null) {
            return null;
        }

        if ($isCreate === true) {
            $system = new EarthAgeSystem();
            $system->setCustom(true);

            return $system;
        }

        // @phpstan-ignore-next-line
        if ($id === null) {
            return null;
        }

        return $this->find($id);
    }

    public function save(EarthAgeSystem $earthAgeSystem): void
    {
        $this->_em->persist($earthAgeSystem);
        $this->_em->flush();
    }

    public function delete(EarthAgeSystem $earthAgeSystem): void
    {
        $this->_em->remove($earthAgeSystem);
        $this->_em->flush();
    }

    public function getColumnCount(): int
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSystem')
            ->select(['COUNT(earthAgeSystem.id)']);

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
            ->from('earth_age_system')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
