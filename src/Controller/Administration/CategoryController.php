<?php

namespace App\Controller\Administration;

use App\Defaults;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Static\Pagination\Pagination;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TranslatorInterface    $translator,
        private readonly CategoryRepository     $categoryRepository
    ) {}

    #[Route('/admin/category', name: 'app_admin_category')]
    public function categoryIndex(Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $searchTerm = trim($request->get('searchTerm', ''));
        if ($searchTerm === '') {
            $searchTerm = null;
        }

        $columnCount = $this->categoryRepository->getColumnCount($searchTerm);
        $paginationResult = Pagination::calculate($columnCount, $page);

        $categories = $this->categoryRepository->getSearchResult($paginationResult->getOffset(), $searchTerm);

        return $this->render(
            'administration/category/index.html.twig',
            array_merge(
                [
                    'categories' => $categories,
                    'searchTerm' => $searchTerm,
                ],
                $paginationResult->toArray()
            )
        );
    }

    #[Route('/admin/category/save', name: 'app_admin_category_save')]
    public function saveCategory(Request $request, Validator $validator): Response {
        $requestData = $request->request->all();

        $validationResult = $validator->validate(Category::class, $requestData);
        if ($validationResult->hasViolations()) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.category.messages.errors.noCategoryName'));

            return $this->redirectToRoute('app_admin_category');
        }

        $category = new Category();
        $category->setName($requestData['name']);

        try {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(
                Defaults::FLASH_TYPE_ERROR,
                sprintf($this->translator->trans('admin.category.messages.errors.cannotCreateCategory'),
                    $requestData['name'],
                    $exception->getMessage()
                )
            );

            return $this->redirectToRoute('app_admin_category');
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.category.messages.created'));

        return $this->redirectToRoute('app_admin_category');
    }

    #[Route('/admin/category/edit', name: 'app_admin_category_edit')]
    public function editCategory(Request $request): Response {
        $categoryId = $request->get('id');
        if ($categoryId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.category.messages.errors.emptyId'));

            return $this->redirectToRoute('app_admin_category');
        }

        $newCategoryName = $request->get('name');
        if ($newCategoryName === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.category.messages.errors.noCategoryName'));

            return $this->redirectToRoute('app_admin_category');
        }

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if (!$category instanceof Category) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.category.messages.errors.cannotFindCategory'), $categoryId));

            return $this->redirectToRoute('app_admin_category');
        }

        $category->setName($newCategoryName);
        try {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.genericError') . $exception->getMessage());

            return $this->redirectToRoute('app_admin_category');
        }
        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericSaved'));

        return $this->redirectToRoute('app_admin_category');
    }

    #[Route('/admin/category/delete', name: 'app_admin_category_delete')]
    public function deleteCategory(Request $request): Response {
        $categoryId = $request->get('categoryId');
        if ($categoryId === null) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.category.messages.errors.emptyId'));

            return $this->redirectToRoute('app_admin_category');
        }

        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);
        if (!$category instanceof Category) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, sprintf($this->translator->trans('admin.category.messages.errors.cannotFindCategory'), $categoryId));

            return $this->redirectToRoute('app_admin_category');
        }

        try {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash(Defaults::FLASH_TYPE_ERROR, $this->translator->trans('admin.genericError') . $exception->getMessage());

            return $this->redirectToRoute('app_admin_category');
        }

        $this->addFlash(Defaults::FLASH_TYPE_SUCCESS, $this->translator->trans('admin.genericDeleted'));

        return $this->redirectToRoute('app_admin_category');
    }
}
