<?php

namespace App\Repository;

use App\Defaults;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Tag> findAll()
 * @method array<Tag> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
     * @param ?string $searchTerm
     *
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getRelationExportList(int $limit, int $offset): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('fossil_tag')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getRelationColumnCount(): int
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['COUNT(*)'])
            ->from('fossil_tag')
            ->executeQuery()
            ->fetchOne();
    }

    public function save(Tag $tag): Tag
    {
        $this->getEntityManager()->persist($tag);
        $this->getEntityManager()->flush();

        return $tag;
    }

    /**
     * @return array<int, Tag>
     */
    public function getGalleryList(): array
    {
        $tagsIdsWithFossil = $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['DISTINCT tag_id'])
            ->from('fossil_tag')
            ->executeQuery()
            ->fetchFirstColumn();

        $result = $this->createQueryBuilder('tag')
            ->select(['tag'])
            ->where('tag.id IN (:tagIds)')
            ->setParameter('tagIds', $tagsIdsWithFossil, ArrayParameterType::INTEGER)
            ->orderBy('tag.name')
            ->getQuery()
            ->getResult();

        if (!is_array($result)) {
            return [];
        }

        return $result;
    }
}
