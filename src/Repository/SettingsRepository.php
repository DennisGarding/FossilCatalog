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
        if ($settings->getId() === null) {
            $reflectionProperty = (new \ReflectionClass(Settings::class))->getProperty('id');
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($settings, 1);
        }

        return $settings;
    }

    public function save(Settings $settings): void
    {
        $this->_em->persist($settings);
        $this->_em->flush();
    }
}
