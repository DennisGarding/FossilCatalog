<?php

namespace App\FossilFormField;

use Symfony\Contracts\Translation\TranslatorInterface;

class FossilFormFieldDefaults
{
    public const TYPE_TEXT = 'TEXT';
    public const TYPE_TEXT_AREA = 'TEXTAREA';
    public const TYPE_NUMBER = 'NUMBER';
    public const TYPE_DATE = 'DATE';
    public const TYPE_BOOL = 'BOOLEAN';

    public const GROUP_GENERAL = 'GENERAL';
    public const GROUP_DISCOVERY = 'DISCOVERY';
    public const GROUP_STRATIGRAPHY = 'STRATIGRAPHY';
    public const GROUP_TAXONOMY = 'TAXONOMY';
    public const GROUP_IMAGE = 'IMAGE';
    public const GROUP_OTHER = 'OTHER';

    public const ALWAYS_ACTIVE_FIELDS = [
        'number',
        'showInGallery',
    ];

    public function __construct(private readonly TranslatorInterface $translator) {}

    /**
     * @return array<int, string>
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_TEXT_AREA,
            self::TYPE_NUMBER,
            self::TYPE_DATE,
            self::TYPE_BOOL,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getSearchableTypes(): array
    {
        return [
            self::TYPE_TEXT,
            self::TYPE_TEXT_AREA,
            self::TYPE_NUMBER,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getTypesTranslated(): array
    {
        return [
            self::TYPE_TEXT => $this->translator->trans('admin.formFields.types.text'),
            self::TYPE_TEXT_AREA => $this->translator->trans('admin.formFields.types.textarea'),
            self::TYPE_NUMBER => $this->translator->trans('admin.formFields.types.number'),
            self::TYPE_DATE => $this->translator->trans('admin.formFields.types.date'),
            self::TYPE_BOOL => $this->translator->trans('admin.formFields.types.boolean'),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function getGroups(): array
    {
        return [
            self::GROUP_GENERAL,
            self::GROUP_DISCOVERY,
            self::GROUP_STRATIGRAPHY,
            self::GROUP_TAXONOMY,
            self::GROUP_IMAGE,
            self::GROUP_OTHER,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getGroupsTranslated(): array
    {
        return [
            self::GROUP_GENERAL => $this->translator->trans('admin.formFields.groups.general'),
            self::GROUP_DISCOVERY => $this->translator->trans('admin.formFields.groups.discovery'),
            self::GROUP_STRATIGRAPHY => $this->translator->trans('admin.formFields.groups.stratigraphy'),
            self::GROUP_TAXONOMY => $this->translator->trans('admin.formFields.groups.taxonomy'),
            self::GROUP_IMAGE => $this->translator->trans('admin.formFields.groups.image'),
            self::GROUP_OTHER => $this->translator->trans('admin.formFields.groups.other'),
        ];
    }
}
