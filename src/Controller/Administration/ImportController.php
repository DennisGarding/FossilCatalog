<?php

namespace App\Controller\Administration;

use App\ImportExport\Import\ImportFileUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    public function __construct(
        private readonly ImportFileUploadService $importFileUploadService,
    ) {}

    #[Route('/admin/import', name: 'app_admin_import')]
    public function importIndex(): Response
    {
        return $this->render('administration/import/index.html.twig', [
            'importUploads' => [],
        ]);
    }

    #[Route('/admin/import/upload/file', name: 'app_admin_import_upload_file')]
    public function uploadImportFile(Request $request): Response
    {
        $uploadedImportFile = $request->files->get('importFile');

        $this->importFileUploadService->upload($uploadedImportFile);

        return $this->redirectToRoute('app_admin_import');
    }
}
