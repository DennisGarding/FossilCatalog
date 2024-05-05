<?php

namespace App\Controller\Update;

use App\Update\Updater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    public function __construct(
        private readonly Updater $updater,
    ) {}

    #[Route('/admin/update/check', name: 'admin_update_check')]
    public function checkForUpdate(): JsonResponse
    {
        try {
            $hasUpdate = $this->updater->checkForUpdates();
        } catch (\throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse($hasUpdate->toArray());
    }

    #[Route('/admin/update/download', name: 'admin_update_download')]
    public function downloadUpdate(): JsonResponse
    {
        try {
            $this->updater->downloadUpdate();
        } catch (\throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse();
    }

    #[Route('/admin/update/extract', name: 'admin_update_extract')]
    public function extract(): JsonResponse
    {
        try {
            $this->updater->extractUpdate();
        } catch (\throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse();
    }

    #[Route('/admin/update/execute', name: 'admin_update_execute')]
    public function execute(): JsonResponse
    {
        try {
            $this->updater->executeUpdate();
        } catch (\throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse();
    }

    #[Route('/admin/update/cleanup', name: 'admin_update_cleanup')]
    public function cleanup(): JsonResponse
    {
        try {
            $this->updater->cleanup();
        } catch (\throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse();
    }
}
