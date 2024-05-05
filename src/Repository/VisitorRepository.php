<?php

namespace App\Repository;

use App\Entity\Visitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visitor>
 *
 * @method Visitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visitor[]    findAll()
 * @method Visitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visitor::class);
    }

    public function findByLongTermKey(string $longTermKey): ?Visitor
    {
        return $this->findOneBy(['longTermKey' => $longTermKey]);
    }

    public function getVisitor(string $longTermKey): Visitor
    {
        $visitor = $this->findByLongTermKey($longTermKey);
        if ($visitor instanceof Visitor) {
            return $visitor;
        }

        $visitor = new Visitor();
        $visitor->setLongTermKey($longTermKey);
        $visitor->setCreatedAt(new \DateTimeImmutable());
        $visitor->setUpdatedAt(new \DateTimeImmutable());
        $visitor->setVisits(0);

        return $visitor;
    }

    public function save(Visitor $visitor): void
    {
        $this->_em->persist($visitor);
        $this->_em->flush();
    }
}
