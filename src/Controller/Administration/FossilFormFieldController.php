<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\FossilFormField;
use App\FossilFormField\FilterBuilder;
use App\FossilFormField\FormFieldFilterOptionService;
use App\FossilFormField\FossilFormFieldDefaults;
use App\Repository\FossilFormFieldRepository;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FossilFormFieldController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface        $router,
        private readonly EntityManagerInterface       $entityManager,
        private readonly FossilFormFieldRepository    $fossilFormFieldRepository,
        private readonly FossilFormFieldDefaults      $fossilFormFieldDefaults,
        private readonly TranslatorInterface          $translator,
        private readonly FormFieldFilterOptionService $formFieldFilterOptionService
    ) {}

    #[Route('/admin/formField', name: 'app_admin_fossil_form_field')]
    public function fossilFormFieldIndex(Request $request): Response
    {
        $filterBuilder = (new FilterBuilder())
            ->addRequestValue(FilterBuilder::TYPE_TYPES, $request->get('fieldTypeFilter'))
            ->addRequestValue(FilterBuilder::TYPE_GROUPS, $request->get('fieldGroupFilter'))
            ->addRequestValue(FilterBuilder::TYPE_CUSTOM, $request->get('customFieldsFilter'));

        $fossilFormFields = $this->fossilFormFieldRepository->findBy($filterBuilder->buildCriteria());

        return $this->render('administration/fossil_form_field/index.html.twig', [
            'fossilFormFields' => $fossilFormFields,
            'formFieldTypes' => $this->formFieldFilterOptionService->createTypeOptions(),
            'formFieldGroups' => $this->formFieldFilterOptionService->createGroupOptions(),
            'customFields' => $this->formFieldFilterOptionService->createCustomFiledOptions(),
            'filterSelection' => $filterBuilder->buildSelection(),
        ]);
    }

    #[Route('/admin/formField/create/form', name: 'app_admin_fossil_form_field_create_form')]
    public function fossilFormFieldCreate(): Response
    {
        return $this->render('administration/fossil_form_field/form.html.twig', [
            'fossilFormField' => new FossilFormField(),
            'disableActiveField' => false,
            'errorRoute' => $this->router->generate('app_admin_fossil_form_field_create_form'),
            'formFieldTypes' => $this->fossilFormFieldDefaults->getTypesTranslated(),
            'formFieldGroups' => $this->fossilFormFieldDefaults->getGroupsTranslated(),
        ]);
    }

    #[Route('/admin/formField/edit/form', name: 'app_admin_fossil_form_field_edit_form')]
    public function fossilFormFieldEdit(Request $request): Response
    {
        $fossilFormFieldId = $request->get('formFieldId');
        if (!is_numeric($fossilFormFieldId)) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.genericNoIdProvided')
            );

            return $this->redirectToRoute('app_admin_fossil_form_field');
        }

        $fossilFormField = $this->fossilFormFieldRepository->find($fossilFormFieldId);

        if (!$fossilFormField instanceof FossilFormField) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                sprintf($this->translator->trans('admin.formFields.error.couldNotFindFormField'), $fossilFormFieldId));

            return $this->redirectToRoute('app_admin_fossil_form_field');
        }

        return $this->render('administration/fossil_form_field/form.html.twig', [
            'fossilFormField' => $fossilFormField,
            'disableActiveField' => in_array($fossilFormField->getFieldName(), FossilFormFieldDefaults::ALWAYS_ACTIVE_FIELDS, true),
            'errorRoute' => $this->router->generate('app_admin_fossil_form_field_edit_form', ['formFieldId' => $fossilFormFieldId]),
            'formFieldTypes' => $this->fossilFormFieldDefaults->getTypesTranslated(),
            'formFieldGroups' => $this->fossilFormFieldDefaults->getGroupsTranslated(),
        ]);
    }

    #[Route('/admin/formField/delete', name: 'app_admin_fossil_form_field_delete')]
    public function fossilFormFieldDelete(Request $request): Response
    {
        $fossilFormFieldId = $request->get('formFieldId');
        $fossilFormField = $this->fossilFormFieldRepository->find($fossilFormFieldId);
        if (!$fossilFormField instanceof FossilFormField) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                sprintf($this->translator->trans('admin.formFields.error.couldNotFindFormField'), $fossilFormFieldId));

            return $this->redirectToRoute('app_admin_fossil_form_field');
        }

        if ($fossilFormField->isIsRequiredDefault()) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.formFields.error.couldNotDeleteFormFieldDefault')
            );

            return $this->redirectToRoute('app_admin_fossil_form_field');
        }

        try {
            $this->entityManager->remove($fossilFormField);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.formFields.error.couldNotDeleteFormFieldError') . '<br/>' . $exception->getMessage()
            );

            return $this->redirectToRoute('app_admin_fossil_form_field');
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.formFields.successfullyDeleted'));

        return $this->redirectToRoute('app_admin_fossil_form_field');
    }

    #[Route('/admin/formField/save', name: 'app_admin_fossil_form_field_save')]
    public function fossilFormFieldSave(Request $request, Validator $validator): Response
    {
        $formFieldId = $request->get('id');
        $formField = $formFieldId === null ? new FossilFormField() : $this->fossilFormFieldRepository->find($formFieldId);
        if (!$formField instanceof FossilFormField) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.formFields.error.couldNotCreateFormField'));

            return $this->redirect($request->get('errorRoute'));
        }

        $validationResult = $validator->validate(FossilFormField::class, $request->request->all(), ($formFieldId !== null));
        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $validationResult->getViolations());

            return $this->redirect($request->get('errorRoute'));
        }

        if (!$formField->isIsRequiredDefault()) {
            $formField->setFieldName($request->get('fieldName'));
            $formField->setFieldType($request->get('fieldType'));
            $formField->setFieldGroup($request->get('fieldGroup'));
        }

        $formField->setFieldLabel($request->get('fieldLabel'));
        $formField->setFieldOrder((int) $request->get('fieldOrder'));
        $formField->setShowInOverview((bool) $request->get('showInOverview'));
        $formField->setActive((bool) $request->get('active'));
        $formField->setAllowBlank((bool) $request->get('allowBlank'));

        if ($formField->getId() === null) {
            $formField->setIsRequiredDefault(false);
            $formField->setShowAlways(false);
        }

        try {
            $this->entityManager->persist($formField);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.formFields.error.couldNotCreateFormField') . '<br/>' . $exception->getMessage()
            );

            return $this->redirect($request->get('errorRoute'));
        }

        return $this->redirectToRoute('app_admin_fossil_form_field');
    }

    #[Route('/admin/formField/filter/clear', name: 'app_admin_fossil_form_field_filter_clear')]
    public function fossilFormFieldClearFilter(): Response
    {
        (new FilterBuilder())->clear();

        return $this->redirectToRoute('app_admin_fossil_form_field');
    }
}
