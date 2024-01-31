<?php

namespace App\Twig;

use App\Entity\EntityInterface;
use App\FossilFormField\FossilFieldMapper;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly FossilFieldMapper $fossilFieldMapper,
    ) {}

    public function getFilters()
    {
        return [
            new TwigFilter('mapField', [$this, 'mapField']),
            new TwigFilter('idFilter', [$this, 'idFilter']),
        ];
    }

    public function mapField(string $fieldName): string
    {
       return $this->fossilFieldMapper->mapProperty($fieldName);
    }

    /**
     * @param Collection<EntityInterface> $objects
     * @return array<int>
     */
    public function idFilter(Collection $objects): array
    {
        return array_map(static function (EntityInterface $object) {
            return $object->getId();
        }, $objects->toArray());
    }
}