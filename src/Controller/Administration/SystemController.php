<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\EarthAgeSystem;
use App\Repository\EarthAgeSystemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SystemController extends AbstractController
{
    public function __construct(
        private readonly EarthAgeSystemRepository $earthAgeSystemRepository,
        private readonly TranslatorInterface      $translator,
    ) {}

    #[Route('/admin/system', name: 'app_admin_system')]
    public function index(): Response
    {
        return $this->render('administration/system/index.html.twig', [
            'systems' => $this->earthAgeSystemRepository->findAll(),
        ]);
    }

    #[Route('/admin/system/save', name: 'app_admin_system_save')]
    public function save(Request $request): Response
    {
        $isCreate = $request->get('create', false);
        $id = $request->get('id');
        if ($isCreate === false && $id === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.system.messages.noIdProvided'));

            return $this->redirectToRoute('app_admin_system');
        }

        $system = $this->earthAgeSystemRepository->getSystem($isCreate, $id);
        if (!$system instanceof EarthAgeSystem) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.system.messages.cannotFindSystem'), $id));

            return $this->redirectToRoute('app_admin_system');
        }

        $system->setName($request->get('name'));
        $system->setActive($request->get('active'));
        $this->earthAgeSystemRepository->save($system);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, sprintf($this->translator->trans('admin.system.messages.systemSaved'), $id));

        return $this->redirectToRoute('app_admin_system');
    }

    #[Route('/admin/system/delete', name: 'app_admin_system_delete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        if ($id === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.system.messages.noIdProvided'));

            return $this->redirectToRoute('app_admin_system');
        }

        $system = $this->earthAgeSystemRepository->find($id);
        if (!$system instanceof EarthAgeSystem) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.system.messages.cannotFindSystem'), $id));

            return $this->redirectToRoute('app_admin_system');
        }

        $this->earthAgeSystemRepository->delete($system);

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, sprintf($this->translator->trans('admin.system.messages.systemDeleted'), $system->getName()));

        return $this->redirectToRoute('app_admin_system');
    }
}
