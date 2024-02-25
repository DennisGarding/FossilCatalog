<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\Fossil;
use App\FossilFormField\FossilFieldMapper;
use App\Images\ImageUploadService;
use App\Repository\CategoryRepository;
use App\Repository\EarthAgeSeriesRepository;
use App\Repository\EarthAgeStageRepository;
use App\Repository\EarthAgeSystemRepository;
use App\Repository\FossilFormFieldRepository;
use App\Repository\FossilRepository;
use App\Repository\FossilRepository\FilterBuilder;
use App\Repository\TagRepository;
use App\Static\Pagination\Pagination;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FossilController extends AbstractController
{
    public function __construct(
        private readonly UrlGeneratorInterface $router,
        private readonly EntityManagerInterface $entityManager,
        private readonly FossilRepository $fossilRepository,
        private readonly FossilFormFieldRepository $fossilFormFieldRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly TagRepository $tagRepository,
        private readonly EarthAgeSystemRepository $earthAgeSystemRepository,
        private readonly EarthAgeSeriesRepository $earthAgeSeriesRepository,
        private readonly EarthAgeStageRepository $earthAgeStageRepository,
        private readonly TranslatorInterface $translator,
        private readonly FossilFieldMapper $fieldMapper,
    ) {}

    #[Route('/admin/fossil/list', name: 'app_admin_fossil_list')]
    public function list(Request $request): Response
    {
        $page = (int) $request->get('page', 1);

        $filterBuilder = (new FilterBuilder())
            ->addRequestValue('searchTerm', $request->get('searchTerm'))
            ->addRequestValue('category', $request->get('category', []))
            ->addRequestValue('tag', $request->get('tag', []))
            ->addRequestValue('system', $request->get('system', []))
            ->addRequestValue('series', $request->get('series', []))
            ->addRequestValue('stage', $request->get('stage', []));

        $columnCount = $this->fossilRepository->getColumnCount($filterBuilder->build());
        $paginationResult = Pagination::calculate($columnCount, $page);

        $fossilList = $this->fossilRepository->getSearchResult($paginationResult->getOffset(), $filterBuilder->build());

        return $this->render('administration/fossil/index.html.twig',
            array_merge(
                [
                    'formFields' => $this->fossilFormFieldRepository->findActive(),
                    'fossilList' => $fossilList,
                    'categories' => $this->categoryRepository->findAll(),
                    'tags' => $this->tagRepository->findAll(),
                    'systems' => $this->earthAgeSystemRepository->findAllActive(),
                    'series' => $this->earthAgeSeriesRepository->findAll(),
                    'stages' => $this->earthAgeStageRepository->findAll(),
                    'filterSelection' => $filterBuilder->build(),
                ],
                $paginationResult->toArray()
            )
        );
    }

    #[Route('/admin/fossil/detail', name: 'app_admin_fossil_detail')]
    public function detail(Request $request): Response
    {
        $fossilId = $request->get('id');
        if ($fossilId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.fossil.error.couldNotFindFossilWithEmptyId'));

            return $this->redirectToRoute('app_admin_fossil_list');
        }

        $fossil = $this->fossilRepository->find($fossilId);
        if (!$fossil instanceof Fossil) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.fossil.error.couldNotFindFossilWithId'), $fossilId));

            return $this->redirectToRoute('app_admin_fossil_list');
        }

        $formFields = $this->fossilFormFieldRepository->findActive();
        $this->fieldMapper->mapGetter($formFields, $fossil);

        return $this->render('administration/fossil/detail.html.twig', [
            'fossil' => $fossil,
            'fossilFormFields' => $formFields,
        ]);
    }

    #[Route('/admin/fossil/create/form', name: 'app_admin_fossil_create_form')]
    public function create(): Response
    {
        return $this->render('administration/fossil/form.html.twig', [
            'fossil' => new Fossil(),
            'errorRoute' => $this->router->generate('app_admin_fossil_form_field_create_form'),
            'fossilFormFields' => $this->fossilFormFieldRepository->findActive(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            'systems' => $this->earthAgeSystemRepository->findAllActive(),
            'series' => $this->earthAgeSeriesRepository->findAll(),
            'stages' => $this->earthAgeStageRepository->findAll(),
        ]);
    }

    #[Route('/admin/fossil/edit/form', name: 'app_admin_fossil_edit_form')]
    public function edit(Request $request): Response
    {
        $fossilId = $request->get('id');
        if ($fossilId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.fossil.error.couldNotFindFossilWithEmptyId'));

            return $this->redirect($request->get('errorRoute'));
        }

        $fossil = $this->fossilRepository->find($fossilId);
        if (!$fossil instanceof Fossil) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.fossil.error.couldNotFindFossilWithId'), $fossilId));

            return $this->redirect($request->get('errorRoute'));
        }

        $fossilFormFields = $this->fossilFormFieldRepository->findActive();
        $this->fieldMapper->mapGetter($fossilFormFields, $fossil);

        return $this->render('administration/fossil/form.html.twig', [
            'fossil' => $fossil,
            'errorRoute' => $this->router->generate('app_admin_fossil_edit_form'),
            'fossilFormFields' => $fossilFormFields,
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            'systems' => $this->earthAgeSystemRepository->findAllActive(),
            'series' => $this->earthAgeSeriesRepository->findAll(),
            'stages' => $this->earthAgeStageRepository->findAll(),
        ]);
    }

    #[Route('/admin/fossil/delete', name: 'app_admin_fossil_delete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $number = $request->get('number');

        $fossil = $this->fossilRepository->find($id);

        if (!$fossil instanceof Fossil) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.fossil.error.couldNotFindFossilWithId'), $id));

            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        }

        if ($fossil->getNumber() !== $number) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.fossil.error.numberDoesNotMatch'), $id));

            return new JsonResponse(Response::HTTP_BAD_REQUEST);
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, sprintf($this->translator->trans('admin.fossil.deletedMessage'), $number));

        $this->entityManager->remove($fossil);
        $this->entityManager->flush();

        return new JsonResponse(Response::HTTP_OK);
    }

    #[Route('/admin/fossil/save', name: 'app_admin_fossil_save')]
    public function save(Request $request, Validator $validator, ImageUploadService $imageUploadService): Response
    {
        $fossilId = $request->get('id');
        $fossil = $fossilId === null ? new Fossil() : $this->fossilRepository->find($fossilId);
        if (!$fossil instanceof Fossil) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.fossil.error.couldNotInstanceFossil'));

            return $this->redirect($request->get('errorRoute'));
        }

        $requestData = $request->request->all();
        $validationResult = $validator->validate(Fossil::class, $requestData);
        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $validationResult->getViolations());

            return $this->redirect($request->get('errorRoute'));
        }

        $tagIds = array_map(function ($item) {
            return $item[0];
        }, $requestData['tags'] ?? []);

        $categoryIds = array_map(function ($item) {
            return $item[0];
        }, $requestData['categories'] ?? []);

        $requestData['tags'] = $this->tagRepository->findBy(['id' => $tagIds]);
        $requestData['categories'] = $this->categoryRepository->findBy(['id' => $categoryIds]);

        try {
            $requestData['dateOfDiscovery'] = new \DateTime($requestData['dateOfDiscovery']);
        } catch (\Exception $exception) {
            // TODO: Add correct error message
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.fossil.error.couldNotUploadImages') . '<br/>' . $exception->getMessage()
            );

            return $this->redirect($request->get('errorRoute'));
        }

        $fossil->apply($requestData);

        try {
            $images = $imageUploadService->uploadFiles($fossil);

            foreach ($images as $image) {
                $fossil->addImage($image);
            }
        } catch (\Exception $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.fossil.error.couldNotUploadImages') . '<br/>' . $exception->getMessage()
            );

            return $this->redirect($request->get('errorRoute'));
        }

        $extraFields = [];
        $formFields = $this->fossilFormFieldRepository->findActiveCustom();
        foreach ($formFields as $formField) {
            $extraFields[$formField->getFieldName()] = $requestData[$formField->getFieldName()] ?? null;
        }

        $fossil->setExtraFields($extraFields);

        if ($fossil->getCreatedAt() === null) {
            $fossil->setCreatedAt(new \DateTimeImmutable());
        }

        $fossil->setUpdatedAt(new \DateTimeImmutable());

        try {
            $this->entityManager->persist($fossil);
            $this->entityManager->flush();
        } catch (\Throwable $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                $this->translator->trans('admin.fossil.error.couldNotSaveFossil') . '<br/>' . $exception->getMessage()
            );

            return $this->redirect($request->get('errorRoute'));
        }

        return $this->redirectToRoute('app_admin_fossil_detail', ['id' => $fossil->getId()]);
    }

    #[Route('/admin/fossil/filter/clear', name: 'app_admin_fossil_filter_clear')]
    public function clearFilters(): Response
    {
        (new FilterBuilder())->clear();

        return $this->redirectToRoute('app_admin_fossil_list');
    }

    #[Route('/admin/fossil/create/pdf', name: 'app_admin_fossil_create_pdf')]
    public function createPdf(Request $request): Response
    {
        $fossilId = $request->get('id');
        if ($fossilId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.fossil.error.couldNotFindFossilWithEmptyId'));

            return $this->redirectToRoute('app_admin_fossil_list');
        }

        $fossil = $this->fossilRepository->find($fossilId);
        if (!$fossil instanceof Fossil) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.fossil.error.couldNotFindFossilWithId'), $fossilId));

            return $this->redirectToRoute('app_admin_fossil_list');
        }

        $formFields = $this->fossilFormFieldRepository->findActive();
        $this->fieldMapper->mapGetter($formFields, $fossil);

        $html = $this->render('administration/fossil/pdf.html.twig', [
            'fossil' => $fossil,
            'fossilFormFields' => $formFields,
            'isPdf' => true,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return new Response(
            $dompdf->stream('resume', ['Attachment' => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}
