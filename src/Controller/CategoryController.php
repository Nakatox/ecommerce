<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'get_categories', methods: ['GET'])]
    public function getAllCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $response = $categoryRepository->findAll();

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'No categories found'
                ],
                Response::HTTP_OK
            );
        }

        return $this->json([
                'message' => 'Categories found',
                'categories' => $response,
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}', name: 'get_category', methods: ['GET'])]
    public function getCategory(CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $response = $categoryRepository->find($id);

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'Category not found'
                ],
                404
            );
        }

        return $this->json([
            'message' => 'Category found',
            'category' => $response
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}/products', name: 'get_category_products', methods: ['GET'])]
    public function getCategoryProducts(CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $response = $categoryRepository->find($id);

        if (empty($response)) {
            return $this->json(
                [
                    'message' => 'Category not found'
                ],
                404
            );
        }

        return $this->json([
            'message' => 'Category found',
            'category' => $response,
            'products' => $response->getProducts()
            ],
            Response::HTTP_OK
        );
    }

    // create a new category
    #[Route('/', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request, CategoryRepository $categoryRepository): JsonResponse
    {

        $category = new Category();

        $form = $this->createForm(
            CategoryType::class,
            $category
        );

        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(
                [
                    'message' => 'Invalid data'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $categoryRepository->save($category, true);

            return $this->json(
                [
                    'message' => 'Category created',
                    'category' => [
                        'name' => $category->getName()
                    ]
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error creating category',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{id}', name: 'update_category', methods: ['PUT'])]
    public function updateCategory(Request $request, CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (empty($category)) {
            return $this->json(
                [
                    'message' => 'Category not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        $form = $this->createForm(
            CategoryType::class,
            $category
        );

        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json(
                [
                    'message' => 'Invalid data'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $categoryRepository->save($category, true);

            return $this->json(
                [
                    'message' => 'Category updated',
                    'category' => $category
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error updating category',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    #[Route('/{id}', name: 'delete_category', methods: ['DELETE'])]
    public function deleteCategory(CategoryRepository $categoryRepository, int $id): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if (empty($category)) {
            return $this->json(
                [
                    'message' => 'Category not found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $categoryRepository->remove($category, true);

            return $this->json(
                [
                    'message' => 'Category deleted',
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'message' => 'Error deleting category',
                    'error' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
