<?php

namespace App\Repository;

use App\Entity\FindingNotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FindingNotes>
 *
 * @method FindingNotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method FindingNotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method FindingNotes[]    findAll()
 * @method FindingNotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FindingNotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FindingNotes::class);
    }

//    /**
//     * @return FindingNotes[] Returns an array of FindingNotes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FindingNotes
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
