<?php

namespace App\Repository;

use App\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Settings>
 *
 * @method Settings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Settings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Settings[]    findAll()
 * @method Settings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Settings::class);
    }

    public function getSettings(): Settings
    {
        $settings = $this->find(1) ?? new Settings();
        $this->ensureSingleSettingsRow($settings);

        return $settings;
    }

    public function save(Settings $settings): void
    {
        $this->ensureSingleSettingsRow($settings);
        $this->_em->persist($settings);
        $this->_em->flush();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getExportList(): array
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->select(['*'])
            ->from('settings')
            ->where('id = 1')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getColumnCount(): int
    {
        $result = $this->getExportList();
        if (empty($result)) {
            return 0;
        }

        return 1;
    }

    private function ensureSingleSettingsRow(Settings $settings): void
    {
        if ($settings->getId() !== 1) {
            $reflectionProperty = (new \ReflectionClass(Settings::class))->getProperty('id');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($settings, 1);
        }
    }
}
