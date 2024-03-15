<?php

namespace App\Controller\Administration;

use App\Images\ImageUploadService;
use App\Repository\SettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SettingsController extends AbstractController
{
    public function __construct(
        private readonly SettingsRepository $settingsRepository,
        private readonly ImageUploadService $imageUploadService,
    ) {}

    #[Route('/admin/settings', name: 'app_admin_settings')]
    public function index(): Response
    {
        return $this->render('/administration/settings/index.html.twig', [
            'settings' => $this->settingsRepository->getSettings(),
        ]);
    }

    #[Route('/admin/settings/save', name: 'app_admin_settings_save')]
    public function save(Request $request): Response
    {
        $settings = $this->settingsRepository->getSettings();
        $settings->setBrand($request->get('brand'));
        $this->imageUploadService->uploadSettingsBanner($settings);

        $this->settingsRepository->save($settings);

        return $this->redirectToRoute('app_admin_settings');
    }
}
