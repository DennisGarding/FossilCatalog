<?php

namespace App\Resolver;

class IntResolver
{
    /**
     * @param array<mixed> $requestData
     */
    public static function resolve(array $requestData, string $key): ?int
    {
        if (!array_key_exists($key, $requestData)) {
            return null;
        }

        $value = $requestData[$key];
        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }
}
