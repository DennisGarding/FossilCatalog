<?php

namespace App\Controller\Gallery;

use App\Repository\FossilRepository;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    public function __construct(
        private readonly FossilRepository $fossilRepository,
        private readonly SettingsRepository $settingsRepository,
    ) {}

    #[Route('/', name: 'gallery_index')]
    public function index(): Response
    {
        return $this->render('gallery/base.html.twig', [
            'settings' => $this->settingsRepository->getSettings(),
            'fossils' => $this->fossilRepository->getSearchResult(0),
        ]);
    }
}
