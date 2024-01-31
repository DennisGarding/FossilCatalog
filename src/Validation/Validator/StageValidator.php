<?php

namespace App\Validation\Validator;

use App\Entity\EarthAgeStage;
use App\Static\Validation\ValidationResult;
use App\Static\Validation\Validator;
use App\Validation\IdConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Translation\TranslatorInterface;

class StageValidator implements ValidatorInterface
{
    public function __construct(
        private readonly IdConstraint        $idConstraint,
        private readonly TranslatorInterface $translator,
    ) {}

    public function validate(array $data, ?bool $requiresId = false, array $options = []): ValidationResult
    {
        $this->options = $options;
        $data = $this->preValidate($data);

        return Validator::validate($data, $this->getConstraints($requiresId));
    }

    public static function supports(): string
    {
        return EarthAgeStage::class;
    }

    public function getConstraints(?bool $requiresId = false): array
    {
        $constraints = [
            'name' => [
                new NotBlank(['message' => $this->translator->trans('admin.stage.messages.noStageName')]),
                new Type(Validator::TYPE_STRING)
            ],
        ];

        if ($this->options['custom']) {
            $constraints['series'] = [
                new NotBlank(['message' => $this->translator->trans('admin.stage.messages.noSeries')]),
                new Type(Validator::TYPE_NUMERIC)
            ];
        }

        $this->idConstraint->addIdConstraint($requiresId, $constraints);

        return $constraints;
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function preValidate(array $data): array
    {
        $data = $this->removeSpaces($data);
        $data = $this->castToBool($data, 'custom');
        $data = $this->castToInt($data, 'system');

        return $this->castToInt($data, 'id');
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function removeSpaces(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        return $data;
    }

    /**
     * @param array<string,mixed> $data
     * @return array<string,mixed>
     */
    private function castToInt(array $data, string $key): array
    {
        if (!isset($data[$key])) {
            return $data;
        }

        if (!is_numeric($data[$key])) {
            return $data;
        }

        $data[$key] = (int) $data[$key];

        return $data;
    }

    private function castToBool(array $data, string $key): array
    {
        if (!isset($data[$key])) {
            return $data;
        }

        if (!is_numeric($data[$key])) {
            return $data;
        }

        $data[$key] = (bool) $data[$key];

        return $data;
    }
}