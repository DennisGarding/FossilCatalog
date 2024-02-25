<?php

namespace App\Repository;

use App\Entity\FossilFormField;
use App\FossilFormField\FossilFormFieldDefaults;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @extends ServiceEntityRepository<FossilFormField>
 */
class FossilFormFieldRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($registry, FossilFormField::class);
    }

    public function findAll()
    {
        $result = parent::findAll();

        foreach ($result as $formField) {
            if (!$formField->isIsRequiredDefault()) {
                continue;
            }

            $formField->setFieldLabel($this->translator->trans($formField->getFieldLabel()));
        }

        return $result;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $result = parent::findBy($criteria, $orderBy, $limit, $offset);

        foreach ($result as $formField) {
            if (!$formField->isIsRequiredDefault()) {
                continue;
            }

            $formField->setFieldLabel($this->translator->trans($formField->getFieldLabel()));
        }

        return $result;
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        $result = parent::find($id, $lockMode, $lockVersion);
        if (!$result instanceof FossilFormField) {
            return $result;
        }

        if (!$result->isIsRequiredDefault()) {
            return $result;
        }

        $result->setFieldLabel($this->translator->trans($result->getFieldLabel()));

        return $result;
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        $result = parent::findOneBy($criteria, $orderBy);
        if (!$result instanceof FossilFormField) {
            return $result;
        }

        if (!$result->isIsRequiredDefault()) {
            return $result;
        }

        $result->setFieldLabel($this->translator->trans($result->getFieldLabel()));

        return $result;
    }

    /**
     * @return array<FossilFormField>
     */
    public function findFilterableFields(): array
    {
        return $this->createQueryBuilder('formField')
            ->where('formField.active = true')
            ->andWhere('formField.isRequiredDefault = true')
            ->andWhere('formField.fieldType in (:searchableTypes)')
            ->setParameter('searchableTypes', FossilFormFieldDefaults::getSearchableTypes())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<FossilFormField>
     */
    public function findActive(): array
    {
        return $this->findBy(['active' => true]);
    }

    /**
     * @return array<FossilFormField>
     */
    public function findActiveCustom(): array
    {
        return $this->findBy(['active' => true, 'isRequiredDefault' => false]);
    }

    public function getColumnCount(): int
    {
        $queryBuilder = $this->createQueryBuilder('formField')
            ->select(['COUNT(formField.id)']);

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
            ->from('fossil_form_field')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
