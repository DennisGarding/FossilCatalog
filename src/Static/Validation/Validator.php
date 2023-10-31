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

    /**
     * @param array<string, mixed>                 $data
     * @param array<string, array<int, Constraint> $validationConstraints
     */
    public static function validate(array $data, array $validationConstraints): ValidationResult
    {
        $validator = Validation::createValidator();

        $violations = [];
        $counter = 0;
        foreach ($validationConstraints as $key => $constraint) {
            $violation = $validator->validate($data[$key], $constraint);
            if ($violation->count() > 0) {
                ++$counter;
                $violations[$key] = implode('<br />',
                    array_map(static function ($violation) use (&$counter) {
                        return $violation->getMessage();
                    }, $violation->getIterator()->getArrayCopy())
                );
            }
        }

        return new ValidationResult($counter, $violations);
    }
}