<?php

namespace App\Validation\Validator;

use App\Static\Validation\ValidationResult;
use Symfony\Component\Validator\Constraint;

interface ValidatorInterface
{
    /**
     * @param array<string, mixed> $data
     * @param ?bool                $requiresId
     * @param array<mixed>         $options
     */
    public function validate(array $data, ?bool $requiresId = false, array $options = []): ValidationResult;

    public static function supports(): string;

    /**
     * @param ?bool $requiresId
     *
     * @return array<string, array<int, Constraint>>
     */
    public function getConstraints(?bool $requiresId = false): array;
}
