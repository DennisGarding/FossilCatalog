<?php

namespace App\Repository;

use App\Defaults;
use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\Results\TagRepositorySaveResult;
use App\Resolver\IntResolver;
use App\Static\Validation\Validator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getColumnCount(?string $searchTerm = null): int
    {
        $queryBuilder = $this->createQueryBuilder('category')
            ->select(['COUNT(category.id)']);

        if ($searchTerm !== null) {
            $queryBuilder->where('category.name LIKE :searchTerm')
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
        $queryBuilder = $this->createQueryBuilder('category')
            ->select(['category']);

        if ($searchTerm !== null) {
            $queryBuilder->where('category.name LIKE :searchTerm')
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
            ->from('category')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
