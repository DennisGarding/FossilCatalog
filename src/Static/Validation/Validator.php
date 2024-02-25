<?php

namespace App\Static\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

class Validator
{
    public const TYPE_STRING = [
        'type' => 'string',
    ];

    public const TYPE_INT = [
        'type' => 'integer',
    ];

    public const TYPE_NUMERIC = [
        'type' => 'numeric',
    ];

    public const TYPE_BOOLEAN = [
        'type' => 'boolean',
    ];

    /**
     * @param array<string, mixed>                  $data
     * @param array<string, array<int, Constraint>> $validationConstraints
     */
    public static function validate(array $data, array $validationConstraints): ValidationResult
    {
        $validator = Validation::createValidator();

        $violations = [];
        $counter = 0;
        foreach ($validationConstraints as $key => $constraint) {
            $violation = $validator->validate($data[$key] ?? null, $constraint);
            if ($violation->count() > 0) {
                $counter++;
                $violations[$key] = implode('<br />',
                    array_map(static function ($violation) {
                        return $violation->getMessage();
                    }, $violation->getIterator()->getArrayCopy()) // @phpstan-ignore-line
                );
            }
        }

        return new ValidationResult($counter, $violations);
    }
}
