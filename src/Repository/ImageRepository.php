<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null   find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null   findOneBy(array $criteria, array $orderBy = null)
 * @method array<Image> findAll()
 * @method array<Image> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getColumnCount(): int
    {
        $queryBuilder = $this->createQueryBuilder('image')
            ->select(['COUNT(image.id)']);

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
            ->from('image')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getRelationColumnCount(): int
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['COUNT(*)'])
            ->from('fossil_image')
            ->executeQuery()
            ->fetchOne();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getRelationExportList(int $limit, int $offset): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('fossil_image')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function delete(Image $image): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove($image->getPath());
        $filesystem->remove($image->getThumbnailPath());

        $this->getEntityManager()->remove($image);
    }
}
