<?php

namespace App\FossilFormField;

use App\Entity\Fossil;
use App\Entity\FossilFormField;

class FossilFieldMapper
{
    private const GETTER_MAPPING = [
        'showInGallery' => 'isShowInGallery',
        'number' => 'getNumber',
        'dateOfDiscovery' => 'getDateOfDiscovery',
        'foundInCountry' => 'getFoundInCountry',
        'findingPlace' => 'getFindingPlace',
        'coordinates' => 'getCoordinates',
        'findingNotes' => 'getFindingNotes',
        'eaSystem' => 'getEaSystem',
        'eaSeries' => 'getEaSeries',
        'eaStage' => 'getEaStage',
        'formation' => 'getFormation',
        'member' => 'getStratigraphicMember',
        'stratigraphicNotes' => 'getStratigraphicNotes',
        'empire' => 'getEmpire',
        'tribe' => 'getTribe',
        'class' => 'getClass',
        'order' => 'getTaxonomicOrder',
        'family' => 'getFamily',
        'genius' => 'getGenius',
        'species' => 'getSpecies',
        'subspecies' => 'getSubspecies',
        'size' => 'getSize',
        'taxonomyNotes' => 'getTaxonomyNotes',
    ];

    private const PROPPERTY_MAPPING = [
        'showInGallery' => 'showInGallery',
        'number' => 'number',
        'dateOfDiscovery' => 'dateOfDiscovery',
        'foundInCountry' => 'foundInCountry',
        'findingPlace' => 'findingPlace',
        'coordinates' => 'coordinates',
        'findingNotes' => 'findingNotes',
        'eaSystem' => 'eaSystem',
        'eaSeries' => 'eaSeries',
        'eaStage' => 'eaStage',
        'formation' => 'formation',
        'member' => 'stratigraphicMember',
        'stratigraphicNotes' => 'stratigraphicNotes',
        'empire' => 'empire',
        'tribe' => 'tribe',
        'class' => 'class',
        'order' => 'taxonomicOrder',
        'family' => 'family',
        'genius' => 'genius',
        'species' => 'species',
        'subspecies' => 'subspecies',
        'size' => 'size',
        'taxonomyNotes' => 'taxonomyNotes',
    ];

    /**
     * @param array<FossilFormField> $fossilFormFields
     *
     * @return array<FossilFormField>
     */
    public function mapGetter(array $fossilFormFields, Fossil $fossil): array
    {
        foreach ($fossilFormFields as $formField) {
            if ($formField->isIsRequiredDefault() && array_key_exists($formField->getFieldName(), self::GETTER_MAPPING)) {
                if (\in_array($formField->getFieldName(), ['eaSystem', 'eaSeries', 'eaStage'], true)) {
                    $formField->setFieldValue($fossil->{self::GETTER_MAPPING[$formField->getFieldName()]}()->getId());
                    continue;
                }

                $formField->setFieldValue($fossil->{self::GETTER_MAPPING[$formField->getFieldName()]}());

                continue;
            }

            $formField->setFieldValue($fossil->getExtraFields()[$formField->getFieldName()] ?? null);
        }

        return $fossilFormFields;
    }

    public function mapProperty(string $fieldName): string
    {
        if (!array_key_exists($fieldName, self::PROPPERTY_MAPPING)) {
            return $fieldName;
        }

        return self::PROPPERTY_MAPPING[$fieldName];
    }
}
