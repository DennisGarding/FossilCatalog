<?php

namespace App\Controller\Gallery;

use App\Repository\FossilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    public function __construct(
        private readonly FossilRepository $fossilRepository
    ) {}

    #[Route('/', name: 'gallery_index')]
    public function index(): Response
    {
        return $this->render('gallery/base.html.twig', [
            'banner' => null,
            'fossils' => $this->fossilRepository->getSearchResult(0),
        ]);
    }
}
