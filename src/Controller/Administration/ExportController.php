<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\ImportExport\Export\ExportServiceInterface;
use App\Repository\ExportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExportController extends AbstractController
{
    public function __construct(
        private readonly ExportServiceInterface $exportService,
        private readonly ExportRepository $exportRepository,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/admin/export', name: 'app_admin_export')]
    public function index(): Response
    {
        return $this->render('administration/export/index.html.twig', [
            'data' => $this->exportService->analyzeData(),
            'exports' => $this->exportRepository->getList(),
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
    public function clear(): JsonResponse
    {
        $this->exportService->clearSession();

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.export.exportComplete'));

        return new JsonResponse(['message' => 'ok'], Response::HTTP_OK);
    }

    #[Route('/admin/export/download/zip', name: 'app_admin_download_zip')]
    public function exportZipDownload(Request $request): Response
    {
        $directory = $request->get('directory');
        $fileName = $request->get('name');

        if (!is_string($directory)) {
            return new JsonResponse(['message' => 'No export "directory" provided'], Response::HTTP_BAD_REQUEST);
        }

        if (!is_string($fileName)) {
            return new JsonResponse(['message' => 'No export "file name" provided'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $zipFile = $this->exportService->createZipFile($directory, $fileName);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if (!is_file($zipFile)) {
            return new JsonResponse(['message' => 'There is a problem with the ZipFile'], Response::HTTP_BAD_REQUEST);
        }

        $response = new BinaryFileResponse($zipFile, Response::HTTP_OK, [], false);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            basename($zipFile)
        );

        return $response;
    }

    #[Route('/admin/export/delete', name: 'app_admin_delete_export')]
    public function deleteExportZip(Request $request): Response
    {
        $directory = $request->get('directory');
        if (!is_string($directory)) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.export.couldNotFindExport'));
        }

        $this->exportService->deleteBackup($directory);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.export.exportDeleted'));

        return $this->redirectToRoute('app_admin_export');
    }
}
