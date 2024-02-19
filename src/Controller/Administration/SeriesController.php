<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\EarthAgeSeries;
use App\Repository\EarthAgeSeriesRepository;
use App\Repository\EarthAgeSystemRepository;
use App\Repository\EarthAgeSeriesRepository\FilterBuilder;
use App\Validation\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SeriesController extends AbstractController
{
    public function __construct(
        private readonly Validator                $validator,
        private readonly EarthAgeSystemRepository $earthAgeSystemRepository,
        private readonly EarthAgeSeriesRepository $earthAgeSeriesRepository,
        private readonly TranslatorInterface      $translator,
    ) {}

    #[Route('/admin/series', name: 'app_admin_series')]
    public function index(Request $request): Response
    {
        $filterBuilder = (new FilterBuilder())
            ->addRequestValue('system', $request->get('systemFilter'))
            ->addRequestValue('custom', $request->get('customFilter'))
            ->addRequestValue('searchTerm', $request->get('searchTerm'));

        $filters = $filterBuilder->build();

        return $this->render('administration/series/index.html.twig', [
            'seriesList' => $this->earthAgeSeriesRepository->findByFilter($filters),
            'systemList' => $this->earthAgeSystemRepository->findAll(),
            'filterSelection' => $filters,
        ]);
    }

    #[Route('/admin/series/save', name: 'app_admin_series_save')]
    public function save(Request $request): Response
    {
        $isCreate = (bool) $request->get('create', false);

        $validationResult = $this->validator->validate(EarthAgeSeries::class, $request->request->all(), !$isCreate, ['custom' => $isCreate]);
        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $validationResult->getViolations());

            return $this->redirectToRoute('app_admin_series');
        }

        $series = $this->earthAgeSeriesRepository->getSeries($isCreate, $request->get('id'));
        if (!$series instanceof EarthAgeSeries) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.series.messages.cannotFindSystem'), $request->get('id')));

            return $this->redirectToRoute('app_admin_series');
        }

        $series->setName($request->get('name'));
        if ($isCreate === true) {
            $systemId = $request->get('system');
            $system = $this->earthAgeSystemRepository->find($systemId);
            $series->setEarthAgeSystem($system);
        }

        $this->earthAgeSeriesRepository->save($series);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.series.messages.seriesSaved'));

        return $this->redirectToRoute('app_admin_series');
    }

    #[Route('/admin/series/delete', name: 'app_admin_series_delete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        if ($id === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.series.messages.noIdProvided'));

            return $this->redirectToRoute('app_admin_series');
        }

        $series = $this->earthAgeSeriesRepository->find($id);
        if (!$series instanceof EarthAgeSeries) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.series.messages.cannotFindSeries'), $id));

            return $this->redirectToRoute('app_admin_series');
        }

        try {
            $this->earthAgeSeriesRepository->delete($series);
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericError') . $exception->getMessage());
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, sprintf($this->translator->trans('admin.series.messages.seriesDeleted'), $series->getName()));

        return $this->redirectToRoute('app_admin_series');
    }

    #[Route('/admin/series/clear/filter', name: 'app_admin_series_clear_filter')]
    public function clearFilter(): Response
    {
        (new FilterBuilder())->clear();

        return $this->redirectToRoute('app_admin_series');
    }
}
