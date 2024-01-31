<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\EarthAgeStage;
use App\Repository\EarthAgeSeriesRepository;
use App\Repository\EarthAgeStageRepository;
use App\Repository\EarthAgeStageRepository\FilterBuilder;
use App\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class StageController extends AbstractController
{
    public function __construct(
        private readonly Validator                $validator,
        private readonly EarthAgeSeriesRepository $earthAgeSeriesRepository,
        private readonly EarthAgeStageRepository $earthAgeStageRepository,
        private readonly TranslatorInterface      $translator,
    ) {}

    #[Route('/admin/stage', name: 'app_admin_stage')]
    public function index(Request $request): Response
    {
        $filterBuilder = (new FilterBuilder())
            ->addRequestValue('series', $request->get('seriesFilter'))
            ->addRequestValue('custom', $request->get('customFilter'))
            ->addRequestValue('searchTerm', $request->get('searchTerm'));

        $filters = $filterBuilder->build();

        return $this->render('administration/stage/index.html.twig', [
            'stageList' => $this->earthAgeStageRepository->findByFilter($filters),
            'seriesList' => $this->earthAgeSeriesRepository->findAll(),
            'filterSelection' => $filters,
        ]);
    }

    #[Route('/admin/stage/save', name: 'app_admin_stage_save')]
    public function save(Request $request): Response
    {
        $isCreate = (bool) $request->get('create', false);
        $validationResult = $this->validator->validate(EarthAgeStage::class, $request->request->all(), !$isCreate, ['custom' => $isCreate]);

        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $validationResult->getViolations());

            return $this->redirectToRoute('app_admin_stage');
        }

        $stage = $this->earthAgeStageRepository->getStage($isCreate, $request->get('id'));
        if (!$stage instanceof EarthAgeStage) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.stage.messages.cannotFindStage'), $request->get('id')));

            return $this->redirectToRoute('app_admin_stage');
        }

        $stage->setName($request->get('name'));
        if ($isCreate === true) {
            $seriesId = $request->get('series');
            $series = $this->earthAgeSeriesRepository->find($seriesId);
            $stage->setEarthAgeSeries($series);
        }

        $this->earthAgeStageRepository->save($stage);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.stage.messages.stageSaved'));

        return $this->redirectToRoute('app_admin_stage');
    }

    #[Route('/admin/stage/delete', name: 'app_admin_stage_delete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        if ($id === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.stage.messages.noIdProvided'));

            return $this->redirectToRoute('app_admin_series');
        }

        $stage = $this->earthAgeStageRepository->find($id);
        if (!$stage instanceof EarthAgeStage) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.stage.messages.cannotFindStage'), $id));

            return $this->redirectToRoute('app_admin_series');
        }

        try {
            $this->earthAgeStageRepository->delete($stage);
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericError') . $exception->getMessage());
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, sprintf($this->translator->trans('admin.stage.messages.stageDeleted'), $stage->getName()));

        return $this->redirectToRoute('app_admin_series');
    }

    #[Route('/admin/srage/clear/filter', name: 'app_admin_stage_clear_filter')]
    public function clearFilter(): Response
    {
        (new FilterBuilder())->clear();

        return $this->redirectToRoute('app_admin_stage');
    }
}
