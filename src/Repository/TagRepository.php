<?php

namespace App\Repository;

use App\Defaults;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getColumnCount(?string $searchTerm = null): int
    {
        $queryBuilder = $this->createQueryBuilder('tag')
            ->select(['COUNT(tag.id)']);

        if ($searchTerm !== null) {
            $queryBuilder->where('tag.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $result = $queryBuilder->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        return (int) $result;
    }

    /**
     * @return array<int, Tag>
     */
    public function getSearchResult(int $offset, ?string $searchTerm = null): array
    {
        $queryBuilder = $this->createQueryBuilder('tag')
            ->select(['tag']);

        if ($searchTerm !== null) {
            $queryBuilder->where('tag.name LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $result = $queryBuilder->setFirstResult($offset)
            ->setMaxResults(Defaults::QUERY_LIMIT)
            ->getQuery()
            ->getResult();

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getExportList(int $limit, int $offset): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('tag')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
