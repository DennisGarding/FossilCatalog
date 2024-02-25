<?php

namespace App\Repository\EarthAgeSeriesRepository;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;

class FilterBuilder
{
    private const CACHE_KEY = 'series_filter';

    /**
     * @var array<string, mixed>
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
        $this->buildSearchTerm();
        $this->buildCustom();
        $this->buildSystem();

        $this->save();

        return $this->filters;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->filters[$key] ?? $default;
    }

    public function clear(): void
    {
        $this->filters = [
            'searchTerm' => null,
            'custom' => false,
            'system' => null,
        ];

        $this->save();
    }

    private function save(): void
    {
        $this->cacheItem->set($this->filters);
        $this->cacheAdapter->save($this->cacheItem);
    }

    private function buildSearchTerm(): void
    {
        if (empty(trim($this->filters['searchTerm']))) {
            $this->filters['searchTerm'] = null;

            return;
        }

        $this->filters['searchTerm'] = trim($this->filters['searchTerm']);
    }

    private function buildCustom(): void
    {
        if (empty($this->filters['custom'])) {
            $this->filters['custom'] = false;

            return;
        }

        $this->filters['custom'] = true;
    }

    private function buildSystem(): void
    {
        if (empty($this->filters['system'])) {
            $this->filters['system'] = null;

            return;
        }

        $this->filters['system'] = (int) $this->filters['system'];
    }
}