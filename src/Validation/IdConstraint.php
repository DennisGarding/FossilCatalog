<?php

namespace App\Validation;

use App\Static\Validation\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Translation\TranslatorInterface;

class IdConstraint
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    /**
     * @param array<string, array<int, Constraint>> $constraints
     *
     * @return array<string, array<int, Constraint>>
     */
    public function addIdConstraint(bool $requiresId, array &$constraints): array
    {
        if ($requiresId) {
            $constraints['id'] = [
                new NotBlank(['message' => $this->translator->trans('admin.category.messages.errors.emptyId')]),
                new Type(Validator::TYPE_INT),
            ];
        }

        return $constraints;
    }
}
