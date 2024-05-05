<?php

namespace App\Controller\Gallery;

use App\Repository\CategoryRepository;
use App\Repository\FossilRepository;
use App\Repository\FossilRepository\FilterBuilder;
use App\Repository\SettingsRepository;
use App\Repository\TagRepository;
use App\Static\Pagination\Pagination;
use App\SystemSeriesStage\SystemSeriesStageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    public function __construct(
        private readonly SettingsRepository $settingsRepository,
        private readonly FossilRepository $fossilRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly TagRepository $tagRepository,
        private readonly SystemSeriesStageService $systemSeriesStageService,
    ) {}

    #[Route('/', name: 'gallery_index')]
    public function index(Request $request): Response
    {
        $page = (int) $request->get('page', 1);

        $filterBuilder = (new FilterBuilder(FilterBuilder::GALLERY_CACHE_KEY))
            ->addRequestValue('searchTerm', $request->get('searchTerm'))
            ->addRequestValue('category', $request->get('category', []))
            ->addRequestValue('tag', $request->get('tag', []))
            ->addRequestValue('system', $request->get('system', []))
            ->addRequestValue('series', $request->get('series', []))
            ->addRequestValue('stage', $request->get('stage', []))
            ->addRequestValue('showInGallery', true);

        $columnCount = $this->fossilRepository->getColumnCount($filterBuilder->build());
        $paginationResult = Pagination::calculate($columnCount, $page);
        $systemSeriesStageResult = $this->systemSeriesStageService->findAllActiveUsed();

        return $this->render('gallery/base.html.twig',
            \array_merge([
                'settings' => $this->settingsRepository->getSettings(),
                'fossilList' => $this->fossilRepository->getSearchResult($paginationResult->getOffset(), $filterBuilder->build()),
                'categories' => $this->categoryRepository->getGalleryList(),
                'tags' => $this->tagRepository->getGalleryList(),
                'systems' => $systemSeriesStageResult->getActiveSystems(),
                'series' => $systemSeriesStageResult->getActiveSeries(),
                'stages' => $systemSeriesStageResult->getActiveStages(),
                'filterSelection' => $filterBuilder->build(),
            ], $paginationResult->toArray())
        );
    }

    #[Route('/filter/clear', name: 'gallery_index_filter_clear')]
    public function clearFilter(): Response
    {
        $filterBuilder = new FilterBuilder(FilterBuilder::GALLERY_CACHE_KEY);
        $filterBuilder->clear();

        return $this->redirectToRoute('gallery_index');
    }
}
