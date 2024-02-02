<?php

namespace App\Controller\Administration;

use App\ImportExport\Export\ExportServiceInterface;
use App\Repository\ExportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    public function __construct(
        private readonly ExportServiceInterface $exportService,
        private readonly ExportRepository       $exportRepository,
    ) {}

    #[Route('/admin/export', name: 'app_admin_export')]
    public function index(): Response
    {
        return $this->render('administration/export/index.html.twig', [
            'data' => $this->exportService->analyzeData(),
            'exports' => $this->exportRepository->getExports(),
        ]);
    }

    #[Route('/admin/export/progress', name: 'app_admin_export_progress')]
    public function exportProgress(): Response
    {
        try {
            $this->exportService->initializeFiles();
            $status = $this->exportService->export();
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => 'ok', 'status' => $status], Response::HTTP_OK);
    }

    #[Route('/admin/export/clear', name: 'app_admin_export_clear')]
    public function clear(): JsonResponse {
        $this->exportService->clearSession();

        return new JsonResponse(['message' => 'ok'], Response::HTTP_OK);
    }
}
