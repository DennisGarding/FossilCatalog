<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\ImportExport\Import\ImportFileUploadService;
use App\ImportExport\Import\ImportService;
use App\Repository\ImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImportController extends AbstractController
{
    public function __construct(
        private readonly ImportFileUploadService $importFileUploadService,
        private readonly ImportRepository $importRepository,
        private readonly ImportService $importService,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/admin/import', name: 'app_admin_import')]
    public function importIndex(): Response
    {
        return $this->render('administration/import/index.html.twig', [
            'imports' => $this->importRepository->getList(),
        ]);
    }

    #[Route('/admin/import/analyze', name: 'app_admin_import_analyze')]
    public function analyze(Request $request): Response
    {
        $zipFile = $request->get('path');

        try {
            $directory = $this->importService->extractZipFile($zipFile);
            $status = $this->importService->analyzeData($directory);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => 'ok', 'status' => $status], Response::HTTP_OK);
    }

    #[Route('/admin/import/progress', name: 'app_admin_import_progress')]
    public function import(): Response
    {
        try {
            $status = $this->importService->import();
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage(), 'trace' => $exception->getTraceAsString()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['message' => 'ok', 'status' => $status], Response::HTTP_OK);
    }

    #[Route('/admin/import/upload/file', name: 'app_admin_import_upload_file')]
    public function uploadImportFile(Request $request): Response
    {
        $uploadedImportFile = $request->files->get('importFile');

        try {
            $this->importFileUploadService->upload($uploadedImportFile);
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $exception->getMessage() . '<br><br>' . $exception->getTraceAsString());

            return $this->redirectToRoute('app_admin_import');
        }

        return $this->redirectToRoute('app_admin_import');
    }

    #[Route('/admin/import/delete/file', name: 'app_admin_import_delete_file')]
    public function delete(Request $request): Response
    {
        $path = $request->get('path');
        if (!is_string($path)) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.import.noFileSelected'));

            return $this->redirectToRoute('app_admin_import');
        }

        $this->importRepository->delete($path);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.import.fileDeleted'));

        return $this->redirectToRoute('app_admin_import');
    }

    #[Route('/admin/import/clear', name: 'app_admin_import_clear')]
    public function clear(): JsonResponse
    {
        $this->importService->clearSession();

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.import.importComplete'));

        return new JsonResponse(['message' => 'ok'], Response::HTTP_OK);
    }
}
