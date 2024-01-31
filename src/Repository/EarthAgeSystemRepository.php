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
 * @method EarthAgeSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method EarthAgeSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method EarthAgeSystem[]    findAll()
 * @method EarthAgeSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EarthAgeSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EarthAgeSystem::class);
    }

    public function findNamesById(array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSystem')
            ->select('earthAgeSystem.name')
            ->where('earthAgeSystem.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::INTEGER);

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);
    }

    public function findAllActive()
    {
        $queryBuilder = $this->createQueryBuilder('earthAgeSystem')
            ->select('earthAgeSystem')
            ->where('earthAgeSystem.active = true');

        return $queryBuilder->getQuery()->getResult();
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

}
