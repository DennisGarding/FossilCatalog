<?php

namespace App\Controller;

use App\Translations\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TranslationController extends AbstractController
{
    #[Route('/translations/load', name: 'load_translations')]
    public function index(TranslationService $translationService): JsonResponse
    {
        try {
            $translations = $translationService->getTranslations();
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($translations);
    }
}
