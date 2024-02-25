<?php

namespace App\Validation\Validator;

use App\Entity\Fossil;
use App\Entity\FossilFormField;
use App\FossilFormField\FossilFormFieldDefaults;
use App\Repository\FossilFormFieldRepository;
use App\Static\Validation\ValidationResult;
use App\Static\Validation\Validator;
use App\Validation\IdConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class FossilValidator implements ValidatorInterface
{
    /** @var array<FossilFormField> */
    private array $fossilFormFields = [];

    public function __construct(
        private readonly IdConstraint $idConstraint,
        private readonly TranslatorInterface $translator,
        private readonly FossilFormFieldRepository $fossilRepository,
    ) {}

    public function validate(array $data, ?bool $requiresId = false, array $options = []): ValidationResult
    {
        $this->fossilFormFields = $this->fossilRepository->findActive();

        $data = $this->trim($data);

        return Validator::validate($data, $this->getConstraints($requiresId));
    }

    public static function supports(): string
    {
        return Fossil::class;
    }

    public function getConstraints(?bool $requiresId = false): array
    {
        $constraints = [];

        foreach ($this->fossilFormFields as $formField) {
            if (!$formField->isAllowBlank()) {
                $constraints[$formField->getFieldName()] = [
                    new NotBlank(['message' => sprintf($this->translator->trans('admin.validator.messages.empty'), $formField->getFieldLabel())]),
                ];
            }
        }

        $this->idConstraint->addIdConstraint($requiresId, $constraints);

        return $constraints;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function trim(array $data): array
    {
        foreach ($data as $key => $value) {
            if ($this->isFieldExcluded($key)) {
                continue;
            }

            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        return $data;
    }

    private function isFieldExcluded(string $fieldName): bool
    {
        foreach ($this->fossilFormFields as $formField) {
            if ($formField->getFieldName() !== $fieldName) {
                continue;
            }

            return $formField->getFieldType() === FossilFormFieldDefaults::TYPE_TEXT_AREA;
        }

        return false;
    }
}
