<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Static\Pagination\Pagination;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface $translator,
        private readonly TagRepository $tagRepository
    ) {}

    #[Route('/admin/tags', name: 'app_admin_tags')]
    public function tagIndex(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $searchTerm = trim($request->get('searchTerm', ''));
        if ($searchTerm === '') {
            $searchTerm = null;
        }

        $columnCount = $this->tagRepository->getColumnCount($searchTerm);
        $paginationResult = Pagination::calculate($columnCount, $page);

        $tags = $this->tagRepository->getSearchResult($paginationResult->getOffset(), $searchTerm);

        return $this->render(
            'administration/tag/index.html.twig',
            array_merge(
                [
                    'tags' => $tags,
                    'searchTerm' => $searchTerm,
                ],
                $paginationResult->toArray()
            )
        );
    }

    #[Route('/admin/tags/save', name: 'app_admin_tags_save')]
    public function saveTag(Request $request, Validator $validator): Response
    {
        $requestData = $request->request->all();

        $validationResult = $validator->validate(Tag::class, $requestData);
        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.tags.messages.errors.noTagName'));

            return $this->redirectToRoute('app_admin_tags');
        }

        $tag = new Tag();
        $tag->setName($requestData['name']);

        try {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                sprintf($this->translator->trans('admin.tags.messages.errors.cannotCreateTag'),
                    $requestData['name'],
                    $exception->getMessage()
                )
            );

            return $this->redirectToRoute('app_admin_tags');
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.tags.messages.created'));

        return $this->redirectToRoute('app_admin_tags');
    }

    #[Route('/admin/tags/edit', name: 'app_admin_tags_edit')]
    public function editTag(Request $request): Response
    {
        $tagId = $request->get('id');
        if ($tagId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.tags.messages.errors.emptyId'));

            return $this->redirectToRoute('app_admin_tags');
        }

        $newTagName = $request->get('name');
        if ($newTagName === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.tags.messages.errors.noTagName'));

            return $this->redirectToRoute('app_admin_tags');
        }

        $tag = $this->entityManager->getRepository(Tag::class)->find($tagId);
        if (!$tag instanceof Tag) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.tags.messages.errors.cannotFindTag'), $tagId));

            return $this->redirectToRoute('app_admin_tags');
        }

        $tag->setName($newTagName);
        try {
            $this->entityManager->persist($tag);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.genericError') . $exception->getMessage());

            return $this->redirectToRoute('app_admin_tags');
        }
        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericSaved'));

        return $this->redirectToRoute('app_admin_tags');
    }

    #[Route('/admin/tags/delete', name: 'app_admin_tags_delete')]
    public function deleteTag(Request $request): Response
    {
        $tagId = $request->get('tagId');
        if ($tagId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.tags.messages.errors.emptyId'));

            return $this->redirectToRoute('app_admin_tags');
        }

        $tag = $this->entityManager->getRepository(Tag::class)->find($tagId);
        if (!$tag instanceof Tag) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.tags.messages.errors.cannotFindTag'), $tagId));

            return $this->redirectToRoute('app_admin_tags');
        }

        try {
            $this->entityManager->remove($tag);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.genericError') . $exception->getMessage());

            return $this->redirectToRoute('app_admin_tags');
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericDeleted'));

        return $this->redirectToRoute('app_admin_tags');
    }
}
