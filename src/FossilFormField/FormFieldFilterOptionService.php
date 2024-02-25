<?php

namespace App\FossilFormField;

use Symfony\Contracts\Translation\TranslatorInterface;

class FormFieldFilterOptionService
{
    /**
     * @var array<string, string>
     */
    private array $allOption;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly FossilFormFieldDefaults   $fossilFormFieldDefaults
    ) {
        $this->allOption = ['ALL' => $this->translator->trans('admin.formFields.filter.all')];
    }

    /**
     * @return array<string, string>
     */
    public function createGroupOptions(): array
    {
        return array_merge(
            $this->allOption,
            $this->fossilFormFieldDefaults->getGroupsTranslated(),
        );
    }

    /**
     * @return array<string, string>
     */
    public function createTypeOptions(): array
    {
        return array_merge(
            $this->allOption,
            $this->fossilFormFieldDefaults->getTypesTranslated(),
        );
    }

    /**
     * @return array<string, string>
     */
    public function createCustomFiledOptions(): array
    {
        return array_merge(
            $this->allOption,
            [
                'CUSTOM_FIELDS' => $this->translator->trans('admin.formFields.filter.customFields'),
                'DEFAULT_FIELDS' => $this->translator->trans('admin.formFields.filter.defaultFields'),
            ]
        );
    }
}