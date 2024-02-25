<?php

namespace App\Repository\FossilRepository;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class FilterBuilder
{
    private const CACHE_KEY = 'fossil_filter';

    /** @var array<string, mixed> */
    private array $filters = [];

    private FilesystemAdapter $cacheAdapter;

    private CacheItem $cacheItem;

    /**
     * @return static
     */
    public function __construct()
    {
        $this->cacheAdapter = new FilesystemAdapter();
        $cachedValues = $this->cacheAdapter->getItem(self::CACHE_KEY);
        $this->cacheItem = $cachedValues;

        if ($cachedValues->isHit()) {
            $this->filters = $this->cacheItem->get();

            return $this;
        }

        $this->clear();

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function addRequestValue(string $type, $value = null): static
    {
        if ($value !== null) {
            $this->filters[$type] = $value;
        }

        $this->save();

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->filters;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->filters[$key] ?? $default;
    }

    private function save(): void
    {
        $this->cacheItem->set($this->filters);
        $this->cacheAdapter->save($this->cacheItem);
    }

    public function clear(): void
    {
        $this->filters = [
            'searchTerm' => '',
            'category' => [],
            'tag' => [],
            'system' => null,
            'series' => null,
            'stage' => null,
        ];

        $this->save();
    }
}
