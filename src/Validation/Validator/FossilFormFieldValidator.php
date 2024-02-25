<?php

namespace App\Validation\Validator;

use App\Entity\FossilFormField;
use App\Static\Validation\ValidationResult;
use App\Static\Validation\Validator;
use App\Validation\IdConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Translation\TranslatorInterface;

class FossilFormFieldValidator implements ValidatorInterface
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
        return FossilFormField::class;
    }

    public function getConstraints(?bool $requiresId = false): array
    {
        $constraints = [
            'fieldName' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyName')]),
                new Type(Validator::TYPE_STRING),
            ],

            'fieldLabel' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyLabel')]),
                new Type(Validator::TYPE_STRING),
            ],

            'fieldType' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyType')]),
                new Type(Validator::TYPE_STRING),
            ],

            'fieldGroup' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyGroup')]),
                new Type(Validator::TYPE_STRING),
            ],

            'showInOverview' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyGroup')]),
                new Type(Validator::TYPE_NUMERIC),
            ],

            'active' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyActive')]),
                new Type(Validator::TYPE_NUMERIC),
            ],

            'allowBlank' => [
                new NotBlank(['message' => $this->translator->trans('admin.formFields.error.emptyAllowEmpty')]),
                new Type(Validator::TYPE_NUMERIC),
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
