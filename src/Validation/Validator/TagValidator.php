<?php

namespace App\Validation\Validator;

use App\Entity\Tag;
use App\Static\Validation\ValidationResult;
use App\Static\Validation\Validator;
use App\Validation\IdConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagValidator implements ValidatorInterface
{
    public function __construct(
        private readonly IdConstraint $idConstraint,
        private readonly TranslatorInterface $translator,
    ) {}

    public function validate(array $data, ?bool $requiresId = false, array $options = []): ValidationResult
    {
        $data = $this->preValidate($data);

        return Validator::validate($data, $this->getConstraints($requiresId));
    }

    public static function supports(): string
    {
        return Tag::class;
    }

    public function getConstraints(?bool $requiresId = false): array
    {
        $constraints = [
            'name' => [
                new NotBlank(['message' => $this->translator->trans('admin.tags.messages.errors.noTagName')]),
                new Type(Validator::TYPE_STRING),
            ],
        ];

        $this->idConstraint->addIdConstraint($requiresId, $constraints);

        return $constraints;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function preValidate(array $data): array
    {
        $data = $this->removeSpaces($data);

        return $this->castIdToInt($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function removeSpaces(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function castIdToInt(array $data): array
    {
        if (!isset($data['id'])) {
            return $data;
        }

        if (!is_numeric($data['id'])) {
            return $data;
        }

        $data['id'] = (int) $data['id'];

        return $data;
    }
}
