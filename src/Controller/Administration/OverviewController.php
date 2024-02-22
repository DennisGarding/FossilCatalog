<?php

namespace App\Controller\Administration;

use App\Repository\CategoryRepository;
use App\Repository\FossilFormFieldRepository;
use App\Repository\FossilRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OverviewController extends AbstractController
{
    public function __construct(
        private readonly FossilRepository $fossilRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly TagRepository $tagRepository,
        private readonly FossilFormFieldRepository $fossilFormFieldRepository,
    ) {}

    #[Route('/admin', name: 'app_admin_overview')]
    public function index(): Response
    {
        return $this->render('administration/overview/index.html.twig', [
            'fossilCount' => $this->fossilRepository->getColumnCount(),
            'categoryCount' => $this->categoryRepository->getColumnCount(),
            'tagCount' => $this->tagRepository->getColumnCount(),
            'latestFossils' => $this->fossilRepository->getLatestFossils(),
            'latestChangedFossils' => $this->fossilRepository->getLatestChangedFossils(),
            'formFields' => $this->fossilFormFieldRepository->findActive(),
        ]);
    }
}
