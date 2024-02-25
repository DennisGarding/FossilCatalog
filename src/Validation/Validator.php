<?php

namespace App\Validation;

use App\Static\Validation\ValidationResult;
use App\Validation\Validator\CategoryValidator;
use App\Validation\Validator\FossilFormFieldValidator;
use App\Validation\Validator\FossilValidator;
use App\Validation\Validator\SeriesValidator;
use App\Validation\Validator\StageValidator;
use App\Validation\Validator\TagValidator;
use App\Validation\Validator\ValidatorInterface;

class Validator
{
    public const EXPECT_ID = true;

    /** @var array<ValidatorInterface> */
    private array $validators = [];

    public function __construct(
        private readonly TagValidator $tagValidator,
        private readonly CategoryValidator $categoryValidator,
        private readonly FossilFormFieldValidator $fossilFormFieldValidator,
        private readonly FossilValidator $fossilValidator,
        private readonly SeriesValidator $seriesValidator,
        private readonly StageValidator $stageValidator,
    ) {
        $this->validators[TagValidator::supports()] = $this->tagValidator;
        $this->validators[CategoryValidator::supports()] = $this->categoryValidator;
        $this->validators[FossilFormFieldValidator::supports()] = $this->fossilFormFieldValidator;
        $this->validators[FossilValidator::supports()] = $this->fossilValidator;
        $this->validators[SeriesValidator::supports()] = $this->seriesValidator;
        $this->validators[StageValidator::supports()] = $this->stageValidator;
    }

    /**
     * @param array<mixed> $data
     * @param ?bool        $requiresId
     * @param array<mixed> $options
     */
    public function validate(string $type, array $data, ?bool $requiresId = false, array $options = []): ValidationResult
    {
        return $this->validators[$type]->validate($data, $requiresId, $options);
    }
}
