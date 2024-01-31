<?php

namespace App\FossilFormField;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class FilterBuilder
{
    public const DEFAULT_VALUE = 'ALL';
    public const TYPE_TYPES = 'fieldType';
    public const TYPE_GROUPS = 'fieldGroup';
    public const TYPE_CUSTOM = 'isRequiredDefault';

    private const CACHE_KEY = 'fossil_form_field_filter';

    /**
     * @var array<string, string>
     */
    private array $filters = [];

    private FilesystemAdapter $cacheAdapter;

    private CacheItem $cacheItem;

    public function __construct()
    {
        $this->cacheAdapter = new FilesystemAdapter();
        $cachedValues = $this->cacheAdapter->getItem(self::CACHE_KEY);
        $this->cacheItem = $cachedValues;

        if ($cachedValues->isHit()) {
            $this->filters = $this->cacheItem->get();

            return $this;
        }

        $this->filters = [
            self::TYPE_TYPES => self::DEFAULT_VALUE,
            self::TYPE_GROUPS => self::DEFAULT_VALUE,
            self::TYPE_CUSTOM => self::DEFAULT_VALUE,
        ];

        return $this;
    }

    public function addRequestValue(string $type, ?string $value = null): static
    {
        if ($value !== null) {
            $this->filters[$type] = $value;
        }

        $this->save();

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function buildSelection(): array
    {
        return $this->filters;
    }

    /**
     * @return array<string, int|string>
     */
    public function buildCriteria(): array
    {
        $criteria = [];
        foreach ($this->filters as $type => $value) {
            if ($value === self::DEFAULT_VALUE) {
                continue;
            }

            if ($type === self::TYPE_CUSTOM) {
                if ($value === 'CUSTOM_FIELDS') {
                    $criteria[$type] = 0;
                }

                if ($value === 'DEFAULT_FIELDS') {
                    $criteria[$type] = 1;
                }
            } else {
                $criteria[$type] = $value;
            }
        }

        return $criteria;
    }

    public function clear(): void
    {
        $this->filters = [
            self::TYPE_TYPES => self::DEFAULT_VALUE,
            self::TYPE_GROUPS => self::DEFAULT_VALUE,
            self::TYPE_CUSTOM => self::DEFAULT_VALUE,
        ];

        $this->save();
    }

    private function save(): void
    {
        $this->cacheItem->set($this->filters);
        $this->cacheAdapter->save($this->cacheItem);
    }

}