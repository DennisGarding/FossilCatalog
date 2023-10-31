<?php

namespace App\Static\Validation;

class ValidationResult
{
    protected bool $hasViolations;

    /**
     * @param array<string, string> $violations
     */
    public function __construct(
        protected readonly int   $violationCount,
        protected readonly array $violations,
    ) {
        $this->hasViolations = $this->violationCount > 0;
    }

    public function hasViolations(): bool
    {
        return $this->hasViolations;
    }

    public function getViolationCount(): int
    {
        return $this->violationCount;
    }

    /**
     * @return array<string, string>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    public function getForField(string $fieldName): ?string
    {
        if (array_key_exists($fieldName, $this->violations)) {
            return $this->violations[$fieldName];
        }

        return null;
    }
}